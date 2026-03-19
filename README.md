# Artist Portfolio WordPress Plugin

A comprehensive WordPress plugin that creates a custom post type for artist portfolios with full WPGraphQL integration.

## Features

- **Custom Post Type**: `artwork` with title, editor, and thumbnail support
- **Hierarchical Taxonomies**: 
  - Artwork Categories (e.g., Digital Illustration, Print, Sculpture, Painting)
  - Series (for grouping artworks within categories)
- **Custom Meta Fields**:
  - Size/dimensions
  - Medium
  - Price
  - Creation date
  - Gallery (multiple images)
- **Full WPGraphQL Integration**: All data exposed via GraphQL
- **WordPress Admin UI**: User-friendly meta boxes with media uploader
- **WordPress Coding Standards**: Clean, secure, and well-documented code

## Requirements

- WordPress 5.0+
- PHP 7.4+
- [WPGraphQL](https://wordpress.org/plugins/wp-graphql/) plugin (for GraphQL functionality)

## Installation

1. Download or clone this repository
2. Upload the `artist-portfolio` folder to your `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Install and activate the WPGraphQL plugin for full functionality

## Usage

### Adding Artworks

1. Go to **Artworks > Add New** in your WordPress admin
2. Fill in the artwork title and description
3. Set a featured image
4. Fill in the artwork details (size, medium, price, creation date)
5. Add gallery images using the media uploader
6. Assign categories and series as needed

### GraphQL Queries

Visit **Artworks > GraphQL Examples** in your admin for complete query examples.

#### Basic Query

```graphql
query GetArtworks {
  artworks {
    nodes {
      title
      content
      size
      medium
      price
      date
      gallery {
        sourceUrl
        altText
      }
      artworkCategories {
        nodes {
          name
        }
      }
      seriesItems {
        nodes {
          name
        }
      }
    }
  }
}
```

#### Single Artwork

```graphql
query GetSingleArtwork($id: ID!) {
  artwork(id: $id) {
    title
    content
    size
    medium
    price
    date
    isAvailable
    dimensions {
      width
      height
      unit
    }
    featuredImage {
      node {
        sourceUrl
        altText
      }
    }
    gallery {
      sourceUrl
      altText
    }
    artworkCategories {
      nodes {
        name
        slug
      }
    }
  }
}
```

### Available GraphQL Fields

**Artwork Fields:**
- `size` - String: Artwork dimensions
- `medium` - String: Medium used (oil, watercolor, etc.)
- `price` - String: Price or availability
- `date` - String: Creation date (ISO format)
- `gallery` - [MediaItem]: Array of gallery images
- `isAvailable` - Boolean: Whether artwork is available for purchase
- `formattedPrice` - String: Formatted price display
- `artworkUrl` - String: Permalink to the artwork
- `dimensions` - Object: Parsed width/height/unit

**Taxonomies:**
- `artworkCategories` - Hierarchical categories
- `seriesItems` - Series groupings

## Customization

### Adding New Meta Fields

1. Add the field key to `SA_Artwork_Meta_Fields::META_FIELDS`
2. Add form field in `render_artwork_details_meta_box()`
3. Add save logic in `save_meta_fields()`
4. Register GraphQL field in `SA_Artwork_GraphQL::register_graphql_fields()`

### Modifying Taxonomies

Edit the `SA_Artwork_Taxonomies` class to modify labels, slugs, or add new taxonomies.

### Custom GraphQL Types

Add custom GraphQL object types in the `SA_Artwork_GraphQL` class for complex data structures.

## Security Features

- Nonce verification for all form submissions
- Capability checks (`edit_post`)
- Input sanitization and validation
- SQL injection prevention
- XSS protection

## WordPress Coding Standards

This plugin follows the [WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards):

- Proper naming conventions with `sa_artwork_` prefix
- Comprehensive inline documentation
- Secure coding practices
- Internationalization ready
- Proper hook usage

## File Structure

```
artist-portfolio/
├── artist-portfolio.php          # Main plugin file
├── assets/
│   └── js/
│       └── admin.js             # Admin JavaScript
├── includes/
│   ├── class-sa-artwork-post-type.php    # Post type registration
│   ├── class-sa-artwork-taxonomies.php   # Taxonomy registration
│   ├── class-sa-artwork-meta-fields.php  # Meta fields & admin UI
│   ├── class-sa-artwork-graphql.php      # WPGraphQL integration
│   └── class-sa-artwork-admin.php        # Admin enhancements
└── README.md
```

## REST API Support

The plugin also enables WordPress REST API support with `show_in_rest => true`, making artworks available via:
- `/wp-json/wp/v2/artworks`
- `/wp-json/wp/v2/artwork_categories`
- `/wp-json/wp/v2/series`

## Support

For issues or questions:
1. Check the GraphQL examples in the admin
2. Verify WPGraphQL plugin is active
3. Test queries in the GraphQL IDE (usually `/graphql-ide`)

## License

GPL v2 or later - https://www.gnu.org/licenses/gpl-2.0.html
