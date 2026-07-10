# WooCommerce HSN Code Manager

A lightweight, efficient WordPress plugin that seamlessly integrates HSN (Harmonized System of Nomenclature) Code functionality into your WooCommerce store for better GST compliance and product management.

## Features

- **Product Management:** Adds a dedicated HSN Code field to the General Tab on the WooCommerce product edit page.
- **Order Integration:** Automatically includes the product's HSN Code as line-item meta data when orders are placed.
- **Admin Visibility:** Displays a dedicated "HSN Code" column in the WooCommerce Products list in the WordPress admin area.
- **Import / Export Compatibility:** Fully supports the native WooCommerce CSV Import/Export tool. Automatically maps the `HSN Code` column during imports.
- **REST API Support:** Exposes the `hsn_code` meta field via the WordPress REST API for easy integration with external systems.

## Installation

1. Download or clone this repository.
2. Upload the folder to your `wp-content/plugins/` directory.
3. Activate the **WooCommerce HSN Code Manager** plugin through the 'Plugins' menu in WordPress.

## Usage

Once activated:
- Edit any product in WooCommerce and look for the **HSN Code** field under the **General** tab to enter your 8-digit code.
- View your product list (`Products > All Products`) to see the newly added HSN Code column.
- During CSV import, map your CSV's HSN code column to the `HSN Code` option provided by this plugin.

## Requirements

- WordPress 5.0 or greater.
- WooCommerce installed and activated.

## Authors

- Lalith Raj Reddi
