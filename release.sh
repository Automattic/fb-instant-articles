#!/bin/bash
red=`tput setaf 1`
green=`tput setaf 2`
yellow=`tput setaf 3`
blue=`tput setaf 4`
reset=`tput sgr0`
me='./'`basename "$0"`

#--------------------------------
# Functions
#--------------------------------
function show_help {
cat <<EOF

${green}Usage:${reset}
  ${blue}${me} [-hvs] [-c <command>] [version]${reset}

${green}Arguments:${reset}
  release      - Creates a GitHub released based on existing version tag
  bump_version - Creates a new version tag
  version      - The target version (ex: 3.2.1)

${green}Options:${reset}
  -h            Display this help message
  -v            Verbose mode
  -s            Simulate only (do not release)
  -c <command>  Runs only a single command. Possible commands are:
                  - bump_version: generate a new version tag on the repository
                  - release: release a new version on GitHub

${green}Examples:${reset}

  ${blue}${me} 3.3.0${reset}
    Runs bump_version then release for 3.3.0. This is the default use case.

  ${blue}${me} -c bump_version 3.3.0${reset}
    Generates a new commit on master changing the version to 3.3.0 in
    all relevant files, tags the commit and pushes to remote.

  ${blue}${me} -c release 3.3.0${reset}
    Creates a new Release on GitHub based on the tag 3.3.0 and uploads
    the binary package based on master.
    ${red}IMPORTANT: this will create a new tag if tag 3.3.0 doesn't exist,
    so make sure to bump_version beforehand.${reset}

  ${blue}${me} -v 3.3.0${reset}
    Releases 3.3.0 in verbose mode.

  ${blue}${me} -s 3.3.0${reset}
    Simulates a 3.3.0 release: prints the commands instead of running them.

EOF
}

function invalid_usage {
  printf $red
  echo $@
  echo "Aborted"
  printf $reset
  show_help
  exit -1;
}
function error_message {
  printf $red
  echo $@
  echo "Aborted"
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
selected_cmd='all'

while getopts "hvc:s" opt; do
    case "$opt" in
    h|\?)
        show_help
        exit 0
        ;;
    v)  verbose=1
        ;;
    s)  simulate=1
        ;;
    c)  selected_cmd="${OPTARG}"
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

if ! type "js" > /dev/null; then
  error_message "SpiderMonkey interpreter not found, please install SpiderMonkey before continuing"
else
  message "Found SpiderMonkey"
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
  printf $reset
}

function prompt_password {
  user_response=''
  printf $blue
  printf "%b" "$*"
  printf $red
  read -s user_response
  printf $reset
}

#----------------------
# Commands
#----------------------
function bump_version {

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
  run git add facebook-instant-articlesg.php
  run rm facebook-instant-articles.php-e

  confirm "Commit version bump on master with message 'Bump version to $version'?"
  run git commit -m "Bump version to $version"

  confirm "Create tag $version?"
  run git tag $version

  confirm "Push tag and commit to GitHub?"
  run git push
  run git push --tags

  revert_repo

  echo "🍺  Tag $version created!"
}

function release {

  confirm "Create a new release for $version?"

  if [[ ! -e resty ]]; then
    message "Downloading resty to connect to GitHub API"
    run curl -L http://github.com/micha/resty/raw/master/resty > resty
  fi
  if [[ ! -e jsawk ]]; then
    message "Downloading jsawk to parse info from GitHub API"
    run curl -L http://github.com/micha/jsawk/raw/master/jsawk > jsawk
  fi

  prompt "GitHub access-token (required only for 2fac):"
  github_access_token=$user_response

  if [[ github_access_token ]]; then
    run . resty -W 'https://api.github.com' -H "Authorization: token $github_access_token"
  else
    prompt "GitHub username:"
    github_username=$user_response

    prompt_password "GitHub password:"
    github_password=$user_response

    run . resty -W 'https://api.github.com' -u $github_username:$github_password
  fi

  response=$(run POST /repos/Automattic/facebook-instant-articles-wp/releases "
  {
    \"tag_name\": \"$version\",
    \"target_commitish\": \"master\",
    \"name\": \"$version\",
    \"body\": \"Version $version\",
    \"draft\": false,
    \"prerelease\": false
  }
  ");

  if [[ $response ]]; then
    message "Release $version created!"
  else
    error_message "Couldn't create release"
  fi

  upload_url=$( echo $response | . jsawk -n 'out(this.upload_url)' |  sed -e "s/{[^}]*}//g" )
  release_id=$( echo $response | . jsawk -n 'out(this.id)'  )

  message "Upload URL: $upload_url"

  message "Creating binary file"
  run composer install
  run zip -qr facebook-instant-articles-wp.zip .

  message "Uploading binary for release..."

  if [[ github_access_token ]]; then
    response=$(run curl -H "Authorization: token $github_access_token" -H "Content-Type: application/zip" --data-binary @facebook-instant-articles-wp.zip $upload_url\?name=facebook-instant-articles-wp-$version.zip )
  else
    response=$(run curl -u $github_username:$github_password -H "Content-Type: application/zip" --data-binary @facebook-instant-articles-wp.zip $upload_url\?name=facebook-instant-articles-wp-$version.zip )
  fi

  if [[ $response ]]; then
    echo "🍺  Release $version successfully created"
  else
    error_message "Couldn't upload file"
  fi

}

# Run right command
if [[ $selected_cmd == 'bump_version' ]]; then bump_version; exit 0; fi
if [[ $selected_cmd == 'release' ]]; then release; exit 0; fi
if [[ $selected_cmd == 'all' ]]; then bump_version; release; exit 0; fi
error_message "Invalid command $selected_cmd"
