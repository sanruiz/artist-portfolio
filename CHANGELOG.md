# Changelog

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
