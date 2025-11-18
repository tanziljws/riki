#!/bin/bash

# Script untuk auto-push ke GitHub setiap ada perubahan
# Usage: ./auto-push.sh (akan berjalan terus sampai dihentikan dengan Ctrl+C)

echo "🚀 Auto-push script started..."
echo "Watching for changes and auto-pushing to GitHub..."
echo "Press Ctrl+C to stop"
echo ""

REPO_URL="https://github.com/tanziljws/riki.git"

# Function to push changes
push_changes() {
    echo ""
    echo "📦 Changes detected! Committing and pushing..."
    
    git add -A
    
    # Check if there are changes to commit
    if git diff --staged --quiet; then
        echo "No changes to commit."
        return
    fi
    
    git commit -m "Auto commit: $(date '+%Y-%m-%d %H:%M:%S')" 2>&1
    
    if [ $? -eq 0 ]; then
        echo "✅ Committed successfully"
        
        # Try to push
        git push origin main 2>&1
        
        if [ $? -eq 0 ]; then
            echo "✅ Pushed to GitHub successfully!"
        else
            echo "⚠️  Push failed. Will retry on next change."
        fi
    else
        echo "⚠️  Commit failed."
    fi
    
    echo ""
}

# Initial push attempt
push_changes

# Watch for changes (using git status polling)
while true; do
    sleep 5  # Check every 5 seconds
    
    # Check if there are uncommitted changes
    if ! git diff --quiet || ! git diff --cached --quiet; then
        push_changes
    fi
done

