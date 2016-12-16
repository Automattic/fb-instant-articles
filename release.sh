#!/bin/bash
red=`tput setaf 1`
green=`tput setaf 2`
yellow=`tput setaf 3`
blue=`tput setaf 4`
reset=`tput sgr0`

#--------------------------------
# Functions
#--------------------------------
function show_help {
cat <<EOF

Usage:
  release.sh [-hvs] [version]

Arguments:
  version - The target version (ex: 3.2.1)

Options:
  -h Display this help message
  -v Verbose mode
  -s Simulate only (do not release)

EOF
}

function invalid_usage {
  printf $red
  echo $@
  echo "Aborting..."
  printf $reset
  show_help
  exit -1;
}
function error_message {
  printf $red
  echo $@
  echo "Aborting..."
  printf $reset
  exit -1
}
function message {
  if [[ $verbose == 1 ]]; then
    printf $green
    echo $@
    printf $reset
  fi
}
function run_message {
  if [[ $simulate == 1 ]]; then
    printf $yellow
    echo $@
    printf $reset
  fi
}

#----------------
# Read parameters
#----------------

# A POSIX variable
# Reset in case getopts has been used previously in the shell.
OPTIND=1

# Read options:
verbose=0
simulate=0

while getopts "hvs" opt; do
    case "$opt" in
    h|\?)
        show_help
        exit 0
        ;;
    v)  verbose=1
        ;;
    s)  simulate=1
        ;;
    esac
done

shift $((OPTIND-1))

# Read argument
version=$1

# Validates arguments
if [[ $2 ]]; then
  invalid_usage "Invalid parameters"
fi

if [[ ! $( echo $version | grep -Ee '^[0-9]+\.[0-9]+(\.[0-9]+)?$' ) ]]; then
  invalid_usage "Invalid version provided"
fi

message "Releasing version: $version"
message "Running in verbose mode"
if [[ $simulate == 1 ]]; then
  message "Running in simulation mode"
fi

#---------------------------------
# Check if we have the right tools
#---------------------------------

if ! type "git" > /dev/null; then
  error_message "git not found, please install git before continuing: http://git.org"
else
  message "Found git: $(git --version)"
fi

#------------------------------------
# Check if we are in the right folder
#------------------------------------
if [[ ! -e '.git/config' ]]; then
  error_message "You should run this command from the root directory of your repository."
fi
if [[ ! $( cat .git/config | grep -i 'automattic/facebook-instant-articles-wp') ]]; then
  error_message "You should run this command from the root directory of the facebook-instant-articles-wp repository."
fi

#-------------------
# Manages simulation
#-------------------
function run {
  if [[ $simulate == 1 ]]; then
    run_message $@
  else
    "$@"
  fi
}

function revert_repo {
  if [[ $branch_name != 'master' ]]; then
    message "Going back to $branch_name"
    run git checkout $branch_name
  fi
  if [[ $stash_ref ]]; then
    message "Applying stashed changes"
    run git stash apply $stash_ref
  fi
  if [[ -e resty ]]; then
    rm resty
  fi
}

function confirm {
  confirm=''
  while [[ $confirm != 'a' && $confirm != 'y' ]]; do
    printf $blue
    printf "%b" "$*"
    printf ' (y)es/(a)bort: '
    printf $red
    read -n 1 confirm
    printf "\n"
  done
  if [[ $confirm != 'y' ]]; then
    revert_repo
    error_message 'Execution aborted by the user'
    exit -1
  fi
  printf $reset
}

function prompt {
  user_response=''
  printf $blue
  printf "%b" "$*"
  printf $red
  read user_response
  printf "\n"
}

#----------------------
# Ok, let's get started
#----------------------
message "Stashing current work..."

stash_ref=$(git stash create)
run_message git stash create

if [[ $stash_ref ]]; then
  run git reset --hard
  message "Stashed current work to: $stash_ref"
else
  message "Nothing to stash"
fi

branch_name="$(git symbolic-ref HEAD 2>/dev/null)"
branch_name=${branch_name##refs/heads/}
message "Current branch: $branch_name"

if [[ $branch_name != 'master' ]]; then
  message "Switching to master..."
  run git checkout master
fi

message "Pulling latest version from GitHub"
run git pull --rebase

confirm "Replace stable tag on readme.txt with $version?"
message "Replacing stable tag on readme.txt"
run sed -i -e "s/Stable tag: .*/Stable tag: $version/" ./readme.txt
run git diff
confirm "Add changes to commit?"
run git add readme.txt
run rm readme.txt-e

confirm "Replace version on facebook-instant-articles-wp.php with $version?"
message "Replacing version on facebook-instant-articles-wp.php"
run sed -i -e "s/^ \* Version: .*/ * Version: $version/" facebook-instant-articles.php
run sed -i -e "s/define( 'IA_PLUGIN_VERSION', '[0-9.]*' );/define( 'IA_PLUGIN_VERSION', '$version' );/" facebook-instant-articles.php
run git diff
confirm "Add changes to commit?"
run git add facebook-instant-articles.php
run rm facebook-instant-articles.php-e

confirm "Commit version bump on master with message 'Bump version to $version'?"
run git commit -m "Bump version to $version"

confirm "Create tag $version?"
run git tag $version

confirm "Push tag and commit to GitHub?"
run git push && git push --tags

confirm "Download resty from master to automatically generate release?"
run curl -L http://github.com/micha/resty/raw/master/resty > resty

prompt "GitHub username:"
github_username=$user_response

prompt "GitHub password:"
github_password=$user_response

run . resty -W 'https://api.github.com' -u $github_username:$github_password

confirm "Create a new release for $version?"
run POST /repos/automattic/facebook-instant-articles-wp/releases "
{
  \"tag_name\": \"$version\",
  \"target_commitish\": \"master\",
  \"name\": \"$version\",
  \"body\": \"Version $version\",
  \"draft\": false,
  \"prerelease\": false
}
";

revert_repo
