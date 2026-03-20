# Changelog

## [1.3.0] - 2026-03-19

### Changed
- **ACF PRO Integration**: Migrated all custom fields to ACF PRO for better reliability
- **Gallery Field**: Now uses ACF PRO's native gallery field with drag-and-drop
- **Simplified Architecture**: Removed custom meta box code in favor of ACF

### Added
- **ACF Field Registration**: Programmatic field registration via `acf_add_local_field_group()`
- **Custom GraphQL Type**: `ArtworkGalleryImage` type with full image data
- **Gallery Images Field**: `galleryImages` GraphQL field with sourceUrl, altText, srcSet
- **WPGraphQL ACF Support**: Enabled `show_in_graphql` for all ACF fields

### Fixed
- **Gallery Persistence**: Resolved issue where gallery images weren't saving in Gutenberg
- **GraphQL Resolver**: Fixed gallery field returning null values

### Technical
- ACF PRO handles all meta field saving automatically
- Custom `ArtworkGalleryImage` GraphQL type for proper image data resolution
- Simplified plugin from 800+ lines to ~380 lines

## [1.2.0] - 2026-03-19

### Added
- **Multi-Image Gallery System**: Complete image gallery management for artworks
- **Visual Gallery Interface**: Drag-and-drop image reordering with thumbnail previews
- **WordPress Media Integration**: Direct integration with WordPress media library
- **Gallery Meta Box**: Dedicated admin interface for managing artwork images
- **WPGraphQL Gallery Field**: Full GraphQL support for querying gallery images

## [1.1.0] - 2026-03-19

### Changed
- Major refactor: Simplified plugin architecture
- Consolidated all functionality into single main plugin file

### Fixed
- Resolved plugin activation issues
- Fixed class loading and initialization problems

## [1.0.1] - 2026-03-19

### Fixed
- Fixed plugin activation issue where menu wasn't appearing
- Added proper activation/deactivation hooks

## [1.0.0] - 2026-03-19

### Added
- Initial release
- Custom post type `artwork` with title, editor, and thumbnail support
- Hierarchical taxonomies: Artwork Categories and Series
- Custom meta fields: size, medium, price, date, gallery
- Full WPGraphQL integration
- WordPress admin UI with meta boxes
- Improved plugin activation process for better user experience
- Enhanced post type registration for more reliable admin menu display


All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2024-01-01

### Added
- Initial release of Artist Portfolio plugin
- Custom post type `artwork` with title, editor, and thumbnail support
- Hierarchical taxonomies: Artwork Categories and Series
- Custom meta fields: size, medium, price, date, gallery
- Full WPGraphQL integration with all fields exposed
- WordPress admin UI with meta boxes and media uploader
- Gallery management with drag-and-drop sorting
- Admin column customization for artwork list view
- GraphQL query examples page in admin
- WordPress Coding Standards compliance
- Internationalization support with .pot file
- REST API support
- Security features: nonces, sanitization, capability checks
- Responsive admin interface with dark mode support

### Features
- **Post Type**: Artwork custom post type with GraphQL support
- **Taxonomies**: Categories and Series with hierarchical structure
- **Meta Fields**: Size, medium, price, creation date, and image gallery
- **GraphQL**: Complete integration with custom resolvers
- **Admin UI**: User-friendly interface with drag-and-drop gallery
- **Security**: Comprehensive security measures
- **Performance**: Optimized database queries and lazy loading
- **Accessibility**: Screen reader friendly and keyboard navigation

### Technical Details
- WordPress 5.0+ compatibility
- PHP 7.4+ requirement
- WPGraphQL plugin integration
- jQuery-based admin interface
- CSS3 with responsive design
- WordPress Coding Standards compliance
