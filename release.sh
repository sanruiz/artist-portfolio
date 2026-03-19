#!/bin/bash

# Artist Portfolio Plugin Release Script
# Usage: ./release.sh [version] [--dry-run]

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Default values
DRY_RUN=false
VERSION=""

# Parse arguments
for arg in "$@"; do
    case $arg in
        --dry-run)
            DRY_RUN=true
            shift
            ;;
        *)
            if [[ -z "$VERSION" && $arg =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
                VERSION=$arg
            else
                echo -e "${RED}Error: Invalid version format. Use semantic versioning (e.g., 1.0.1)${NC}"
                exit 1
            fi
            shift
            ;;
    esac
done

# Function to print colored output
print_status() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

print_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Get current version from plugin file
get_current_version() {
    grep "Version:" artist-portfolio.php | sed 's/.*Version: *\([0-9.]*\).*/\1/'
}

# Update version in files
update_version() {
    local new_version=$1
    
    print_status "Updating version to $new_version..."
    
    # Update plugin header
    sed -i.bak "s/Version: .*/Version: $new_version/" artist-portfolio.php
    
    # Update plugin constant
    sed -i.bak "s/SA_ARTWORK_PLUGIN_VERSION', '[^']*'/SA_ARTWORK_PLUGIN_VERSION', '$new_version'/" artist-portfolio.php
    
    # Update changelog
    local today=$(date +"%Y-%m-%d")
    sed -i.bak "1 a\\
\\
## [$new_version] - $today\\
\\
### Changed\\
- Version bump to $new_version\\
" CHANGELOG.md
    
    # Remove backup files
    rm -f artist-portfolio.php.bak CHANGELOG.md.bak
    
    print_success "Version updated to $new_version"
}

# Validate environment
validate_environment() {
    print_status "Validating environment..."
    
    # Check if we're in a git repository
    if ! git rev-parse --git-dir > /dev/null 2>&1; then
        print_error "Not in a git repository"
        exit 1
    fi
    
    # Check if working directory is clean
    if [[ -n $(git status --porcelain) ]]; then
        print_error "Working directory is not clean. Please commit or stash changes."
        exit 1
    fi
    
    # Check if we're on main branch
    local current_branch=$(git branch --show-current)
    if [[ "$current_branch" != "main" ]]; then
        print_warning "Not on main branch (currently on $current_branch)"
        read -p "Continue anyway? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            exit 1
        fi
    fi
    
    print_success "Environment validation passed"
}

# Create release
create_release() {
    local version=$1
    
    if [[ "$DRY_RUN" == "true" ]]; then
        print_warning "DRY RUN MODE - No changes will be made"
        print_status "Would update version to: $version"
        print_status "Would create tag: v$version"
        print_status "Would push to origin"
        return
    fi
    
    # Update version
    update_version "$version"
    
    # Commit changes
    print_status "Committing version changes..."
    git add artist-portfolio.php CHANGELOG.md
    git commit -m "Bump version to $version"
    
    # Create tag
    print_status "Creating tag v$version..."
    git tag -a "v$version" -m "Release version $version"
    
    # Push changes and tag
    print_status "Pushing to origin..."
    git push origin main
    git push origin "v$version"
    
    print_success "Release v$version created successfully!"
    print_status "GitHub Actions will automatically create the release and build artifacts."
    print_status "Check: https://github.com/sanruiz/artist-portfolio/releases"
}

# Main script
main() {
    echo -e "${BLUE}🎨 Artist Portfolio Plugin Release Tool${NC}"
    echo "============================================="
    
    # Get current version
    local current_version=$(get_current_version)
    print_status "Current version: $current_version"
    
    # If no version specified, prompt for it
    if [[ -z "$VERSION" ]]; then
        echo
        echo "Enter new version (current: $current_version):"
        echo "Examples: 1.0.1 (patch), 1.1.0 (minor), 2.0.0 (major)"
        read -p "New version: " VERSION
        
        if [[ ! $VERSION =~ ^[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
            print_error "Invalid version format. Use semantic versioning (e.g., 1.0.1)"
            exit 1
        fi
    fi
    
    # Validate version is newer
    if [[ "$VERSION" == "$current_version" ]]; then
        print_error "New version must be different from current version"
        exit 1
    fi
    
    print_status "Target version: $VERSION"
    echo
    
    # Validate environment
    validate_environment
    echo
    
    # Confirm release
    if [[ "$DRY_RUN" != "true" ]]; then
        print_warning "This will:"
        echo "  - Update version in plugin files"
        echo "  - Update CHANGELOG.md"
        echo "  - Create git commit and tag"
        echo "  - Push to GitHub"
        echo "  - Trigger automatic release build"
        echo
        read -p "Continue with release? (y/N): " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            print_status "Release cancelled"
            exit 0
        fi
    fi
    
    # Create release
    create_release "$VERSION"
}

# Run main function
main "$@"
