#!/bin/bash

# --- CONFIG ---
GIT_EMAIL="mayankmittal@outlook.in"
GIT_NAME="Mayank Mittal"
SSH_KEY_NAME="id_ed25519_github"

# --- 1. Generate new SSH key ---
echo "Generating a new SSH key..."
ssh-keygen -t ed25519 -C "$GIT_EMAIL" -f ~/.ssh/$SSH_KEY_NAME -N ""

# --- 2. Start ssh-agent and add new key ---
echo "Adding SSH key to ssh-agent..."
eval "$(ssh-agent -s)"
ssh-add ~/.ssh/$SSH_KEY_NAME

# --- 3. Print the public key ---
echo "✅ Public key to copy to GitHub:"
echo "-----------------------------------"
cat ~/.ssh/${SSH_KEY_NAME}.pub
echo "-----------------------------------"

# --- 4. Set Git global name and email ---
echo "Setting Git global user.name and user.email..."
git config --global user.name "$GIT_NAME"
git config --global user.email "$GIT_EMAIL"

# --- 5. (Optional) Change author info of existing commits in current repo ---
# Uncomment the following lines if you want to rewrite existing commits

# echo "Rewriting commit history in current repo to update author info..."
# git filter-branch --env-filter "
#     export GIT_AUTHOR_NAME='$GIT_NAME';
#     export GIT_AUTHOR_EMAIL='$GIT_EMAIL';
#     export GIT_COMMITTER_NAME='$GIT_NAME';
#     export GIT_COMMITTER_EMAIL='$GIT_EMAIL';
# " --tag-name-filter cat -- --branches --tags

echo "✅ All done! You can now add your key to GitHub and push with updated email."