# Changelog

## [1.2.0] - 2026-03-19

### Added
- **Multi-Image Gallery System**: Complete image gallery management for artworks
- **Visual Gallery Interface**: Drag-and-drop image reordering with thumbnail previews
- **WordPress Media Integration**: Direct integration with WordPress media library
- **Gallery Meta Box**: Dedicated admin interface for managing artwork images
- **WPGraphQL Gallery Field**: Full GraphQL support for querying gallery images
- **Gallery Admin Columns**: Show gallery image count in artwork list view
- **Interactive JavaScript**: Real-time gallery management with jQuery UI sortable

### Enhanced
- **Admin Experience**: Improved artwork editing with visual gallery management
- **GraphQL API**: Extended with gallery field returning MediaItem objects
- **User Interface**: Modern, responsive gallery grid with overlay controls
- **File Validation**: Automatic validation of image attachments
- **Data Persistence**: Robust saving and retrieval of gallery image order

### Technical
- **Media Library Integration**: Leverages WordPress native media handling
- **Drag & Drop Reordering**: jQuery UI sortable implementation
- **AJAX-like Interactions**: Seamless gallery updates without page reload
- **Image Validation**: Ensures only valid image attachments are stored
- **GraphQL Schema**: Proper MediaItem integration for headless applications

## [1.1.0] - 2026-03-19

### Changed
- Major refactor: Simplified plugin architecture by removing complex class structure
- Consolidated all functionality into single main plugin file for better reliability
- Improved plugin initialization and WordPress compatibility

### Fixed
- Resolved plugin activation issues where admin menu wasn't displaying consistently
- Fixed class loading and initialization problems that caused plugin failures

### Removed
- Removed complex class-based structure in favor of simpler, more reliable approach
- Cleaned up repository by removing test files and debug code

## [1.0.1] - 2026-03-19

### Fixed
- Fixed plugin activation issue where "Artworks" menu wasn't appearing in WordPress admin sidebar
- Added proper activation/deactivation hooks to flush rewrite rules automatically
- Set explicit menu position (20) for consistent placement in admin menu

### Changed
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
