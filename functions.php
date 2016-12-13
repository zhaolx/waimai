<?php

	/* Constants & Globals
	=============================================================== */
    
	// Uncomment to include un-minified JavaScript files
	//define( 'NM_SCRIPT_DEBUG', TRUE );
	
	// Constants: Folder directories/uri's
	define( 'NM_THEME_DIR', get_template_directory() );
	define( 'NM_DIR', get_template_directory() . '/includes' );
	define( 'NM_THEME_URI', get_template_directory_uri() );
	define( 'NM_URI', get_template_directory_uri() . '/includes' );
	
	// Constant: Framework namespace
	define( 'NM_NAMESPACE', 'nm-framework' );
	
	// Constant: Theme version
	define( 'NM_THEME_VERSION', '1.5.5' );
	
	// Global: Theme options
	global $nm_theme_options;
	
	// Global: Page includes
	global $nm_page_includes;
	$nm_page_includes = array();
	
	// Global: <body> class
	global $nm_body_class;
	$nm_body_class = '';
	
	// Global: Theme globals
	global $nm_globals;
	$nm_globals = array();
	
    // Globals: Visual composer "stock" features
    $nm_globals['vcomp_stock'] = ( defined( 'NM_VCOMP_STOCK' ) ) ? true : false;
	
    // Globals: Shop search (keep above "Includes")
    $nm_globals['shop_search_enabled']  = false;
    $nm_globals['shop_search_layout']   = 'shop';
    
    // Globals: Shop header
    $nm_globals['shop_header_centered'] = false;

	// Global: "Product Slider" shortcode loop
	$nm_globals['product_slider_loop'] = false;
	
	// Global: Shop image lazy-loading
	$nm_globals['shop_image_lazy_loading'] = false;
	
	
	
	/* Includes
	=============================================================== */
	
	// Redux: Theme options framework
	if ( ! class_exists( 'ReduxFramework' ) ) {
		require_once( NM_DIR . '/options/ReduxCore/framework.php' );
        
        if ( is_admin() ) {
            // Remove dashboard widget
            function nm_redux_remove_dashboard_widget() {
                remove_meta_box( 'redux_dashboard_widget', 'dashboard', 'side' );
            }
            add_action( 'wp_dashboard_setup', 'nm_redux_remove_dashboard_widget', 100 );
        }
	}
		
	if ( ! isset( $redux_demo ) ) {
		require( NM_DIR . '/options/options-config.php' );
	}
	
	// Get theme options
	$nm_theme_options = get_option( 'nm_theme_options' );
	
	// Is the theme options array saved?
	if ( ! $nm_theme_options ) {
		// Save default options array
		require( NM_DIR . '/options/default-options.php' );
	}
	
    // Content importer
    /*if ( is_admin() ) {    
        require( NM_DIR . '/importer/importer-config.php' );
    }*/

	// TGM plugin activation
	if ( is_admin() ) {
		require( NM_DIR . '/tgmpa/config.php' );
	}
	
	// Helper functions
	require( NM_DIR . '/helpers.php' );
	
	// Post meta
	require( NM_DIR . '/post-meta.php' );
	
	// Visual composer
	require( NM_DIR . '/visual-composer/init.php' );
	
	// Custom CSS
	if ( is_admin() ) {
		require( NM_DIR . '/custom-styles.php' );
	}
	
	if ( nm_woocommerce_activated() ) {
		// Only include if global product hover image is disabled
		if ( is_admin() ) {
			// WooCommerce: Product details meta boxes
			include( NM_DIR . '/woocommerce/admin/product-details-meta-boxes.php' );
			
			// WooCommerce: Product category "title" field
			include( NM_DIR . '/woocommerce/admin/product-category-title-field.php' );
		}
		
		// WooCommerce: Functions
		include( NM_DIR . '/woocommerce/woocommerce-functions.php' );
        // WooCommerce: Template functions
		include( NM_DIR . '/woocommerce/woocommerce-template-functions.php' );
		
		// WooCommerce: Wishlist
		$nm_globals['wishlist_enabled'] = class_exists( 'NM_Wishlist' );
		
		// WooCommerce: Quick view
		if ( $nm_theme_options['product_quickview'] ) {
			$nm_page_includes['quickview'] = true;
			include( NM_DIR . '/woocommerce/quickview.php' );
		}
		
		// WooCommerce: Shop search
        $nm_globals['shop_search_layout'] = ( isset( $_GET['search_layout'] ) ) ? $_GET['search_layout'] : $nm_theme_options['shop_search'];
        if ( $nm_globals['shop_search_layout'] !== '0' ) {
			$nm_globals['shop_search_enabled'] = true;
            
            include( NM_DIR . '/woocommerce/search.php' );
		} else {
            $nm_globals['shop_search_enabled'] = false;
        }
	}
	
	
	
	/* Globals (requires includes)
	=============================================================== */
	
    // Globals: Login link
    $nm_globals['login_popup'] = false;
    
    // Globals: Cart link/panel
	$nm_globals['cart_link']   = false;
	$nm_globals['cart_panel']  = false;

    // Globals: Shop filters popup
    $nm_globals['shop_filters_popup'] = false;

	// Globals: Shop filters scrollbar
	$nm_globals['shop_filters_scrollbar'] = false;
	//$nm_globals['shop_filters_scrollbar_custom']   = false;
	
    // Globals: Shop search
    $nm_globals['shop_search_header']   = false;
    $nm_globals['shop_search']          = false;
    $nm_globals['shop_search_popup']    = false;

	if ( nm_woocommerce_activated() ) {
		// Global: Shop page id
		$nm_globals['shop_page_id'] = ( ! empty( $_GET['shop_page'] ) ) ? intval( $_GET['shop_page'] ) : wc_get_page_id( 'shop' );
		
		// Globals: Login link
		$nm_globals['login_popup'] = ( $nm_theme_options['menu_login_popup'] ) ? true : false;
		
		// Global: Cart link/panel
		if ( $nm_theme_options['menu_cart'] != '0' ) {
			$nm_globals['cart_link'] = true;
			
			// Is mini cart panel enabled?
			if ( $nm_theme_options['menu_cart'] != 'link' ) {
				$nm_globals['cart_panel'] = true;
			}
		}
		
        // Globals: Shop filters popup
        if ( isset( $_GET['filters_popup'] ) || $nm_theme_options['shop_filters'] == 'popup' ) {
            $nm_globals['shop_filters_popup'] = true;
        }
        
		// Globals: Shop filters scrollbar
		/*if ( $nm_theme_options['shop_filters_scrollbar'] !== '0' && $nm_theme_options['shop_filters'] == 'header' ) { // Only enable scrollbars for shop-header filters
			$nm_globals['shop_filters_scrollbar'] = true;
			$nm_globals['shop_filters_scrollbar_custom'] = ( $nm_theme_options['shop_filters_scrollbar'] == 'js' ) ? true : false;
		}*/
        if ( $nm_theme_options['shop_filters_scrollbar'] && $nm_theme_options['shop_filters'] == 'header' ) { // Only enable scrollbars for shop-header filters
			$nm_globals['shop_filters_scrollbar'] = true;
		}
        
        // Globals: Shop search
        if ( $nm_globals['shop_search_enabled'] ) {
            if ( $nm_globals['shop_search_layout'] === 'header' ) {
                $nm_globals['shop_search_header'] = true;
            } else {
                if ( $nm_globals['shop_filters_popup'] ) {
                    $nm_globals['shop_search_popup'] = true; // Show search in filters pop-up
                } else {
                    $nm_globals['shop_search'] = true; // Show search in shop header
                }
            }
        }
	}
	
	
	
	/* Theme Support
	=============================================================== */

	if ( ! function_exists( 'nm_theme_support' ) ) {
		function nm_theme_support() {
			global $nm_theme_options;
			
			if ( isset( $nm_theme_options['custom_title'] ) && ! $nm_theme_options['custom_title'] ) {
				// Let WordPress manage the document title (no hard-coded <title> tag in the document head)
				add_theme_support( 'title-tag' );
			}
			
			// Add menu support
			add_theme_support( 'menus' );
			
			// Enables post and comment RSS feed links to head
			add_theme_support( 'automatic-feed-links' );
			
			// Add WooCommerce support
			add_theme_support( 'woocommerce' );
			
			// Add thumbnail theme support
			add_theme_support( 'post-thumbnails' );
            
			// Add image sizes
			/*add_image_size( 'nm_large', 700, '', true );
			add_image_size( 'nm_medium', 220, '', true );
			add_image_size( 'nm_small', 140, '', true );
			add_image_size( 'nm_blog_list', 940, '', true );*/
            
			// Localisation support
			// WordPress language directory: wp-content/languages/theme-name/en_US.mo
			load_theme_textdomain( 'nm-framework', trailingslashit( WP_LANG_DIR ) . 'nm-framework' );
			// Child theme language directory: wp-content/themes/child-theme-name/languages/en_US.mo
			load_theme_textdomain( 'nm-framework', get_stylesheet_directory() . '/languages' );
			// Theme language directory: wp-content/themes/theme-name/languages/en_US.mo
			load_theme_textdomain( 'nm-framework', NM_THEME_DIR . '/languages' );
		}
	}
	add_action( 'after_setup_theme', 'nm_theme_support' );
	
	// Maximum width for media
	if ( ! isset( $content_width ) ) {
		$content_width = 1220; // Pixels
	}
	
	
	/* Styles
	=============================================================== */
	
	function nm_styles() {
		global $nm_theme_options, $nm_globals;
		
		// Third-party styles				
		wp_enqueue_style( 'normalize', NM_THEME_URI . '/css/third-party/normalize.css', array(), '3.0.2', 'all' );
		wp_enqueue_style( 'slick-slider', NM_THEME_URI . '/css/third-party/slick.css', array(), '1.5.5', 'all' );
		wp_enqueue_style( 'slick-slider-theme', NM_THEME_URI . '/css/third-party/slick-theme.css', array(), '1.5.5', 'all' );
		wp_enqueue_style( 'magnific-popup', NM_THEME_URI . '/css/third-party/magnific-popup.css', array(), '0.9.7', 'all' );
		if ( $nm_theme_options['font_awesome'] ) {
			wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', array(), '4.6.1', 'all' );
		}
		
		// Theme styles: Grid (enqueue before shop styles)
		wp_enqueue_style( 'nm-grid', NM_THEME_URI . '/css/grid.css', array(), NM_THEME_VERSION, 'all' );
		
		// WooCommerce styles		
		if ( nm_woocommerce_activated() ) {
			// Dequeue styles
			wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
			
			// Dequeue WooCommerce scripts
			// Note: Keep these in the "nm_styles()" function ("BWP Minify" includes them otherwise)
			wp_dequeue_script( 'prettyPhoto' );
			wp_dequeue_script( 'prettyPhoto-init' );
			
			// Single product page
			if ( is_product() ) {
				// Single product gallery: Image hover-zoom
				$nm_globals['product_image_hover_zoom'] = ( $nm_theme_options['product_image_hover_zoom'] || isset( $_GET['zoom'] ) );
				
				wp_enqueue_style( 'photoswipe', NM_THEME_URI . '/css/third-party/photoswipe/photoswipe.css', array(), '4.0.0', 'all' );
				wp_enqueue_style( 'photoswipe-skin', NM_THEME_URI . '/css/third-party/photoswipe/photoswipe-skin.css', array(), '4.0.0', 'all' );
			} else {
				if ( is_cart() ) {
					// Widget panel: Disable on "Cart" page
					$nm_globals['cart_panel'] = false;
				} else if ( is_checkout() ) {
					// Widget panel: Disable on "Checkout" page
					$nm_globals['cart_panel'] = false;
					
					// Default checkout page styles
					/*if ( defined( 'NM_SHOP_DEFAULT_CHECKOUT' ) ) {
						wp_enqueue_style( 'nm-shop-default-checkout', NM_THEME_URI . '/css/shop-default-checkout.css', array(), NM_THEME_VERSION, 'all' );
					}*/
				}
			}
			
			wp_enqueue_style( 'selectod', NM_THEME_URI . '/css/third-party/selectod.css', array(), '3.8.1', 'all' );
			wp_enqueue_style( 'nm-shop', NM_THEME_URI . '/css/shop.css', array(), NM_THEME_VERSION, 'all' );
		}
		//Is Login
		if(is_user_logged_in()){
			$nm_globals['is_login'] = true;
		}
		// Theme styles
		wp_enqueue_style( 'nm-icons', NM_THEME_URI . '/css/font-icons/theme-icons/theme-icons.css', array(), NM_THEME_VERSION, 'all' );
		wp_enqueue_style( 'nm-core', NM_THEME_URI . '/style.css', array(), NM_THEME_VERSION, 'all' );
		wp_enqueue_style( 'nm-elements', NM_THEME_URI . '/css/elements.css', array(), NM_THEME_VERSION, 'all' );
	}
	add_action( 'wp_enqueue_scripts', 'nm_styles', 99 );
	
	
	
	/* Custom styles
	=============================================================== */
	
	function nm_custom_styles() {
		$styles = get_option( 'nm_theme_custom_styles' );
		
		// Output pre-escaped custom styles
		echo $styles . "\n";
	}
	add_action( 'wp_head', 'nm_custom_styles', 100 );
	
	
	
	/* Scripts
	=============================================================== */
	
	function nm_scripts() {
		if ( ! is_admin() ) {
			global $nm_theme_options, $nm_globals, $nm_page_includes;
			
			
			// Script path and suffix setup (debug mode loads un-minified scripts)
			if ( defined( 'NM_SCRIPT_DEBUG' ) && NM_SCRIPT_DEBUG ) {
				$script_path = NM_THEME_URI . '/js/dev/';
				$suffix = '';
			} else {
				$script_path = NM_THEME_URI . '/js/';
				$suffix = '.min';
			}
			
			
			// Enqueue scripts
			wp_enqueue_script( 'modernizr', NM_THEME_URI . '/js/plugins/modernizr.min.js', array( 'jquery' ), '2.8.3' );
			wp_enqueue_script( 'unveil', NM_THEME_URI . '/js/plugins/jquery.unveil.min.js', array( 'jquery' ), '1.0' );
			wp_enqueue_script( 'slick-slider', NM_THEME_URI . '/js/plugins/slick.min.js', array( 'jquery' ), '1.5.5' );
			wp_enqueue_script( 'magnific-popup', NM_THEME_URI . '/js/plugins/jquery.magnific-popup.min.js', array( 'jquery' ), '0.9.9' );
			wp_enqueue_script( 'nm-core', $script_path . 'nm-core' . $suffix . '.js', array( 'jquery' ), NM_THEME_VERSION );
			
			
			// Enqueue blog-grid scripts
			if ( isset( $nm_page_includes['blog-grid'] ) )
				wp_enqueue_script( 'packery', NM_THEME_URI . '/js/plugins/packery.pkgd.min.js', array(), '2.0.0', true );
			
			
			// WP comments script
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
			
			
			if ( nm_woocommerce_activated() ) {
				// Register shop/product scripts
				wp_register_script( 'selectod', NM_THEME_URI . '/js/plugins/selectod.custom.min.js', array( 'jquery' ), '3.8.1' );
				wp_register_script( 'nm-shop-add-to-cart', $script_path . 'nm-shop-add-to-cart' . $suffix . '.js', array( 'jquery', 'nm-shop' ), NM_THEME_VERSION );
				wp_register_script( 'nm-shop', $script_path . 'nm-shop' . $suffix . '.js', array( 'jquery', 'nm-core', 'selectod' ), NM_THEME_VERSION );
				wp_register_script( 'wc-add-to-cart-variation', NM_THEME_URI . '/js/woocommerce/add-to-cart-variation.min.js', array( 'jquery' ), '2.x', true ); // Needed for variation product quick views
				wp_register_script( 'nm-shop-quickview', $script_path . 'nm-shop-quickview' . $suffix . '.js', array( 'jquery', 'nm-shop', 'wc-add-to-cart-variation' ), NM_THEME_VERSION );
				wp_register_script( 'nm-shop-login', $script_path . 'nm-shop-login' . $suffix . '.js', array( 'jquery' ), NM_THEME_VERSION );
				
				
				// Enqueue login script
				if ( $nm_globals['login_popup'] ) {
					wp_enqueue_script( 'nm-shop-login' );
				}
				
				
				// Enqueue shop/product scripts
				if ( isset( $nm_page_includes['products'] ) ) {
					wp_enqueue_script( 'selectod' );
					wp_enqueue_script( 'nm-shop-add-to-cart' );
					if ( $nm_theme_options['product_quickview'] ) {
						wp_enqueue_script( 'wc-add-to-cart-variation' ); // Needed for variation product quick views
						wp_enqueue_script( 'nm-shop-quickview' );
					}
				} else if ( isset( $nm_page_includes['wishlist-home'] ) ) {
					wp_enqueue_script( 'nm-shop-add-to-cart' );
				}
				
				
				// Register shop scripts
				wp_register_script( 'nm-shop-infload', $script_path . 'nm-shop-infload' . $suffix . '.js', array( 'jquery', 'nm-shop' ), NM_THEME_VERSION );
				wp_register_script( 'nm-shop-filters', $script_path . 'nm-shop-filters' . $suffix . '.js', array( 'jquery', 'nm-shop' ), NM_THEME_VERSION );
				wp_register_script( 'nm-shop-search', $script_path . 'nm-shop-search' . $suffix . '.js', array( 'jquery', 'nm-shop' ), NM_THEME_VERSION );
				
				
				// WooCommerce page - Note: Does not include the Cart, Checkout or Account pages
				if ( is_woocommerce() ) {
					// Single product page
					if ( is_product() ) {
						// Single product page: Modal gallery
						if ( $nm_theme_options['product_image_zoom'] ) {
							wp_enqueue_script( 'photoswipe', NM_THEME_URI . '/js/plugins/photoswipe.min.js', array( 'jquery' ), '4.0.0' );
							wp_enqueue_script( 'photoswipe-ui', NM_THEME_URI . '/js/plugins/photoswipe-ui-default.min.js', array( 'jquery' ), '4.0.0' );
						}
						// Single product page: Hover image-zoom
						if ( $nm_globals['product_image_hover_zoom'] ) {
							wp_enqueue_script( 'easyzoom', NM_THEME_URI . '/js/plugins/easyzoom.min.js', array( 'jquery' ), '2.3.0' );
						}
						wp_enqueue_script( 'nm-shop-add-to-cart' );
						wp_enqueue_script( 'nm-shop-single-product', $script_path . 'nm-shop-single-product' . $suffix . '.js', array( 'jquery', 'nm-shop' ), NM_THEME_VERSION );
					} 
					// Shop page (except Single product, Cart and Checkout)
					else {
						wp_enqueue_script( 'smartscroll', NM_THEME_URI . '/js/plugins/jquery.smartscroll.min.js', array( 'jquery' ), '1.0' );
						wp_enqueue_script( 'nm-shop-infload' );
						wp_enqueue_script( 'nm-shop-filters' );
						/*if ( $nm_globals['shop_filters_scrollbar_custom'] ) {
							wp_enqueue_script( 'nm-shop-filters-scrollbar', $script_path . 'nm-shop-filters-scrollbar' . $suffix . '.js', array( 'jquery', 'nm-shop-filters' ), NM_THEME_VERSION );
						}*/
						wp_enqueue_script( 'nm-shop-search' );
					}
				} else {
					// Cart page
					if ( is_cart() ) {
						wp_enqueue_script( 'nm-shop-cart', $script_path . 'nm-shop-cart' . $suffix . '.js', array( 'jquery', 'nm-shop' ), NM_THEME_VERSION );
					} 
					// Checkout page
					else if ( is_checkout() ) {
						wp_enqueue_script( 'nm-shop-checkout', $script_path . 'nm-shop-checkout' . $suffix . '.js', array( 'jquery', 'nm-shop' ), NM_THEME_VERSION );
					}
					// Account page
					else if ( is_account_page() ) {
						wp_enqueue_script( 'nm-shop-login' );
					}
				}
			}
			
			
			// Add local Javascript variables
            $local_js_vars = array(
				'themeUri' 				    => NM_THEME_URI,
				'ajaxUrl' 				    => admin_url( 'admin-ajax.php' ),
				'searchUrl'				    => home_url( '?s=' ),
				'pageLoadTransition'        => intval( $nm_theme_options['page_load_transition'] ),
                'shopFiltersAjax'		    => isset( $_GET['ajax_filters'] ) ? esc_attr( $_GET['ajax_filters'] ) : esc_attr( $nm_theme_options['shop_filters_enable_ajax'] ),
				'shopAjaxUpdateTitle'	    => intval( $nm_theme_options['shop_ajax_update_title'] ),
				//'shopFilterScrollbars'	    => ( $nm_globals['shop_filters_scrollbar_custom'] ) ? 1 : 0,
				'shopImageLazyLoad'		    => intval( $nm_theme_options['product_image_lazy_loading'] ),
                'shopScrollOffset' 		    => intval( $nm_theme_options['shop_scroll_offset'] ),
				'shopScrollOffsetTablet'    => intval( $nm_theme_options['shop_scroll_offset_tablet'] ),
                'shopScrollOffsetMobile'    => intval( $nm_theme_options['shop_scroll_offset_mobile'] ),
                'shopSearch'			    => esc_attr( $nm_globals['shop_search_layout'] ),
				'shopSearchMinChar'		    => intval( $nm_theme_options['shop_search_min_char'] ),
				'shopSearchAutoClose'       => intval( $nm_theme_options['shop_search_auto_close'] ),
                'shopAjaxAddToCart'		    => ( get_option( 'woocommerce_enable_ajax_add_to_cart' ) == 'yes' && get_option( 'woocommerce_cart_redirect_after_add' ) == 'no' ) ? 1 : 0,
                'shopRedirectScroll'        => intval( $nm_theme_options['product_redirect_scroll'] ),
                'shopCustomSelect'          => intval( $nm_theme_options['product_custom_select'] ),
                'wpGalleryPopup'            => intval( $nm_theme_options['wp_gallery_popup'] ),
				'referralCode'				=>636
			);
    		wp_localize_script( 'nm-core', 'nm_wp_vars', $local_js_vars );
		}
	}
	add_action( 'wp_footer', 'nm_scripts' ); // Add footer scripts
	
	
	
	/* Admin Assets
	=============================================================== */
	
	function nm_admin_assets( $hook ) {
		// Styles
		wp_enqueue_style( 'nm-admin-styles', NM_URI . '/assets/css/nm-wp-admin.css', array(), NM_THEME_VERSION, 'all' );
		
		// Widgets page
		if ( 'widgets.php' == $hook ) {
			wp_enqueue_style( 'wp-color-picker' );
			
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'nm-wp-color-picker', NM_URI . '/assets/js/nm-color-picker-init.js', array( 'jquery' ), false );
		}
	}
	add_action( 'admin_enqueue_scripts', 'nm_admin_assets' ); // Admin assets
	
	
	
	/* Web fonts
	=============================================================== */
	
	global $webfont_status;
	$webfont_status = array( 'typekit' => false, 'fontdeck' => false );
	
	/* Web fonts: Enqueue scripts */
	function nm_webfonts() {
		global $nm_theme_options, $webfont_status;
		
		// Fontdeck
		if ( $nm_theme_options['main_font_source'] === '3' && isset( $nm_theme_options['fontdeck_project_id'] ) ) {
			$webfont_status['fontdeck'] = true;
		} else {
			// Typekit: Main font kit
			if ( $nm_theme_options['main_font_source'] === '2' && isset( $nm_theme_options['main_font_typekit_kit_id'] ) ) {
				$webfont_status['typekit'] = true;
				wp_enqueue_script( 'nm_typekit_main', '//use.typekit.net/' . esc_attr( $nm_theme_options['main_font_typekit_kit_id'] ) . '.js' );
			}
			
			// Typekit: Secondary font kit
			if ( $nm_theme_options['secondary_font_source'] === '2' && isset( $nm_theme_options['secondary_font_typekit_kit_id'] ) ) {
				// Make sure typekit kit-id's are different (no need to include the same typekit file for both fonts)
				if ( $nm_theme_options['secondary_font_typekit_kit_id'] !== $nm_theme_options['main_font_typekit_kit_id'] ) {
					$webfont_status['typekit'] = true;
					wp_enqueue_script( 'nm_typekit_secondary', '//use.typekit.net/' . esc_attr( $nm_theme_options['secondary_font_typekit_kit_id'] ) . '.js' );
				}
			}
		}
	};
	add_action( 'wp_enqueue_scripts', 'nm_webfonts' );
	
	
	/* Web fonts: Add inline scripts */
	function nm_webfonts_inline() {
		global $webfont_status, $nm_theme_options;
		
		if ( $webfont_status['typekit'] ) {
			//if ( wp_script_is( 'nm_typekit_main', 'done' ) ) {
			echo "\n" . '<script type="text/javascript">try{Typekit.load();}catch(e){}</script>';
			//}
		} else if ( $webfont_status['fontdeck'] ) {
			echo "\n" . "<script type='text/javascript'>WebFontConfig={fontdeck:{id:'" . $nm_theme_options['fontdeck_project_id'] . "'}};(function(){var wf=document.createElement('script');wf.src=('https:'==document.location.protocol?'https':'http')+'://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';wf.type='text/javascript';wf.async='true';var s=document.getElementsByTagName('script')[0];s.parentNode.insertBefore(wf,s);})();</script>";
		}
	};
	add_action( 'wp_head', 'nm_webfonts_inline' );
	
	
	
	/* Redux Framework
	=============================================================== */
	
	/* Remove redux sub-menu from "Tools" admin menu */
	function nm_remove_redux_menu() {
		remove_submenu_page( 'tools.php', 'redux-about' );
	}
	add_action( 'admin_menu', 'nm_remove_redux_menu', 12 );
	
	
	
	/* Theme Setup
	=============================================================== */
	
	if ( $nm_theme_options['custom_title'] ) {
		/* Page title helper: Build title string with site description */
		function nm_build_description_title( $site_title, $sep ) {
			$site_description = get_bloginfo( 'description', 'display' );
			return "$site_title $sep $site_description";
		};
		/* Page title */
		if ( ! function_exists( 'nm_wp_title' ) ) {
			function nm_wp_title( $title, $sep ) {
				if ( is_feed() ) {
					return $title;
				}
			
				$site_title = get_bloginfo( 'name' );
				$sep = apply_filters( 'nm_wp_title_sep', $sep );
				
				// Default homepage
				if ( is_front_page() && is_home() ) {
					$title = nm_build_description_title( $site_title, $sep );
				} 
				// Static homepage
				elseif ( is_front_page() ) {
					$title = nm_build_description_title( $site_title, $sep );
				} 
				// Blog page
				elseif ( is_home() ) {
					$title .= nm_build_description_title( $site_title, $sep ); // Note: Using ".="
				}
				// Everything else
				else {
					$title .= $site_title;
				}
				
				return $title;
			}
		}
		add_filter( 'wp_title', 'nm_wp_title', 10, 2 );
	}
	
	
	/* Front-end WordPress admin bar */
	if ( ! $nm_theme_options['wp_admin_bar'] ) {
		function nm_remove_admin_bar() {		
			return false;
		}
		add_filter( 'show_admin_bar', 'nm_remove_admin_bar' );
	}
	
    
	/* Register menus */
	if ( ! function_exists( 'nm_register_menus' ) ) {
		function nm_register_menus() {
			register_nav_menus( array(
				'top-bar-menu'	=> __( 'Top Bar Menu', 'nm-framework' ),
				'main-menu'		=> __( 'Main Menu', 'nm-framework' ),
				'right-menu'	=> __( 'Right Menu', 'nm-framework' ),
				'footer-menu'	=> __( 'Footer Menu', 'nm-framework' )
			) );
		}
	}
	add_action( 'init', 'nm_register_menus' ); // Register menus
	
	
	/*
	 *	Disable emoji icons
	 * 	Source: https://wordpress.org/plugins/disable-emojis/
	 */
	if ( ! function_exists( 'nm_disable_emojis' ) ) {
		function nm_disable_emojis() {
			remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
			remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
			remove_action( 'wp_print_styles', 'print_emoji_styles' );
			remove_action( 'admin_print_styles', 'print_emoji_styles' );	
			
			remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
			remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );	
			remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
			
			add_filter( 'tiny_mce_plugins', 'nm_disable_emojis_tinymce' );
		}
	}
	/* Filter function: Remove TinyMCE emoji plugin */
	function nm_disable_emojis_tinymce( $plugins ) {
		if ( is_array( $plugins ) ) {
			return array_diff( $plugins, array( 'wpemoji' ) );
		} else {
			return array();
		}
	}
	// Hook: Disable emoji icons
	add_action( 'init', 'nm_disable_emojis' );
	
	
	/* Set number of posts to display in search results */
	/*function nm_wp_search_size( $query ) {
		if ( $query->is_search ) {
			$post_per_page = get_option( 'posts_per_page' );
			$query->query_vars['posts_per_page'] = ( $post_per_page > 10 ) ? $post_per_page : 10;
		}
		
		return $query; // Return our modified query variables
	}
	add_filter( 'pre_get_posts', 'nm_wp_search_size' ); // Hook our custom function onto the request filter*/
	
    
    /* Video embeds: Wrap video element in "div" container (to make them responsive) */
    function nm_wrap_video_embeds( $html ) {
        return '<div class="nm-wp-video-wrap">' . $html . '</div>';
    }
    add_filter( 'embed_oembed_html', 'nm_wrap_video_embeds', 10, 3 );
    add_filter( 'video_embed_html', 'nm_wrap_video_embeds' ); // Jetpack

	
	/* Comments callback */
	function nm_comments( $comment, $args, $depth ) {
		$GLOBALS['comment'] = $comment;
		
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
		?>
		<li class="post pingback">
			<p><?php esc_html_e( 'Pingback:', 'nm-framework' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'nm-framework' ), ' ' ); ?></p>
		<?php
			break;
			default :
		?>
		<li id="comment-<?php comment_ID() ?>" <?php comment_class(); ?>>
            <div class="comment-inner-wrap">
            	<?php if ( function_exists( 'get_avatar' ) ) { echo get_avatar( $comment, '60' ); } ?>
                
				<div class="comment-text">
                    <p class="meta">
                        <strong itemprop="author"><?php printf( '%1$s', get_comment_author_link() ); ?></strong>
                        <time itemprop="datePublished" datetime="<?php echo get_comment_date( 'c' ); ?>"><?php printf( esc_html__( '%1$s at %2$s', 'nm-framework' ), get_comment_date(), get_comment_time() ); ?></time>
                    </p>
                
                    <div itemprop="description" class="description entry-content">
                        <?php if ( $comment->comment_approved == '0' ) : ?>
                            <p class="moderating"><em><?php esc_html_e( 'Your comment is awaiting moderation', 'nm-framework' ); ?></em></p>
                        <?php endif; ?>
                        
                        <?php comment_text(); ?>
                    </div>
                    
                    <div class="reply">
                        <?php 
                            edit_comment_link( esc_html__( 'Edit', 'nm-framework' ), '<span class="edit-link">', '</span><span> &nbsp;-&nbsp; </span>' );
                            
                            comment_reply_link( array_merge( $args, array(
                                'depth' 	=> $depth,
                                'max_depth'	=> $args['max_depth']
                            ) ) );
                        ?>
                    </div>
                </div>
            </div>
		<?php
			break;
		endswitch;
	}
	
	
	
	/* Blog
	=============================================================== */
	
	/* Post excerpt brackets - [...] */
	function nm_excerpt_read_more( $excerpt ) {
		$excerpt_more = '&hellip;';
		$trans = array(
			'[&hellip;]' => $excerpt_more // WordPress >= v3.6
		);
		
		return strtr( $excerpt, $trans );
	}
	add_filter( 'wp_trim_excerpt', 'nm_excerpt_read_more' );
	
	
	/* Blog categories menu */
	function nm_blog_category_menu() {
		global $wp_query, $nm_theme_options;

		$current_cat = ( is_category() ) ? $wp_query->queried_object->cat_ID : '';
		
		// Categories order
		$orderby = 'slug';
		$order = 'asc';
		if ( isset( $nm_theme_options['blog_categories_orderby'] ) ) {
			$orderby = $nm_theme_options['blog_categories_orderby'];
			$order = $nm_theme_options['blog_categories_order'];
		}
		
		$args = array(
			'type'			=> 'post',
			'orderby'		=> $orderby,
			'order'			=> $order,
			'hide_empty'	=> ( $nm_theme_options['blog_categories_hide_empty'] ) ? 1 : 0,
			'hierarchical'	=> 1,
			'taxonomy'		=> 'category'
		); 
		
		$categories = get_categories( $args );
		
		$current_class_set = false;
		$categories_output = '';
		
		// Categories menu divider
		$categories_menu_divider = apply_filters( 'nm_blog_categories_divider', '<span>&frasl;</span>' );
		
		foreach ( $categories as $category ) {
			if ( $current_cat == $category->cat_ID ) {
				$current_class_set = true;
				$current_class = ' class="current-cat"';
			} else {
				$current_class = '';
			}
			$category_link = get_category_link( $category->cat_ID );
			
			$categories_output .= '<li' . $current_class . '>' . $categories_menu_divider . '<a href="' . esc_url( $category_link ) . '">' . esc_attr( $category->name ) . '</a></li>';
		}
		
		$categories_count = count( $categories );
		
		// Categories layout classes
		$categories_class = ' toggle-' . $nm_theme_options['blog_categories_toggle'];
		if ( $nm_theme_options['blog_categories_layout'] === 'columns' ) {
			$column_small = ( intval( $nm_theme_options['blog_categories_columns'] ) > 4 ) ? '3' : '2';
			$categories_ul_class = 'columns small-block-grid-' . $column_small . ' medium-block-grid-' . $nm_theme_options['blog_categories_columns'];
		} else {
			$categories_ul_class = $nm_theme_options['blog_categories_layout'];
		}
		
		// "All" category class attr
		$current_class = ( $current_class_set ) ? '' : ' class="current-cat"';
		
		$output = '<div class="nm-blog-categories-wrap ' . esc_attr( $categories_class ) . '">';
		$output .= '<ul class="nm-blog-categories-toggle"><li><a href="#" id="nm-blog-categories-toggle-link">' . esc_html__( 'Categories', 'nm-framework' ) . '</a> <em class="count">' . $categories_count . '</em></li></ul>';
		$output .= '<ul id="nm-blog-categories-list" class="nm-blog-categories-list ' . esc_attr( $categories_ul_class ) . '"><li' . $current_class . '><a href="' . esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) . '">' . esc_html__( 'All', 'nm-framework' ) . '</a></li>' . $categories_output . '</ul>';
		$output .= '</div>';
		
		return $output;
	}
	
	
	/* Blog slider */
	function nm_get_blog_slider( $post_id, $image_size ) {
		$slider = get_post_gallery( $post_id, false );
		
		if ( $slider ) {
			nm_add_page_include( 'blog-slider' );
						
			$slider_id = "nm-blog-slider-{$post_id}";
			$image_ids = explode( ',', $slider['ids'] );
			$post_permalink = get_permalink();
			
			$slider = "<div id='$slider_id' class='nm-blog-slider slick-slider slick-controls-gray slick-dots-inside slick-dots-centered slick-dots-active-small'>";
		
			foreach ( $image_ids as $image_id ) {
				$image_src = wp_get_attachment_image_src( $image_id, $image_size );
				$slider .= '<div><a href="' . esc_url( $post_permalink ) . '"><img src="' . esc_url( $image_src[0] ) . '" width="' . esc_attr( $image_src[1] ) . '" height="' . esc_attr( $image_src[2] ) . '" /></a></div>';
			}
					
			$slider .= "</div>\n";
		}
		
		return $slider;
	}
	
	
	/* 
	 *	WP gallery (override via action)
	 *	Note: Code inside "// WP default" comments is located in: "../wp-includes/media.php" ("gallery_shortcode()" function)
	 */
	function nm_wp_gallery( $val, $attr ) {
		nm_add_page_include( 'blog-slider' );
		
		// WP default
		$post = get_post();
		
		static $instance = 0;
		$instance++;
		// /WP default
		
		$atts = shortcode_atts( array(
			'order'      => 'ASC',
			'orderby'    => 'menu_order ID',
			'id'         => $post ? $post->ID : 0,
			'itemtag'    => '',
			'icontag'    => '',
			'captiontag' => '',
			'columns'    => 2,
			'size'       => 'blog-list',
			'include'    => '',
			'exclude'    => '',
			'link'       => ''
		), $attr, 'gallery' );
		
		// WP default
		$id = intval( $atts['id'] );
	
		if ( ! empty( $atts['include'] ) ) {
			$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	
			$attachments = array();
			foreach ( $_attachments as $key => $val ) {
				$attachments[$val->ID] = $_attachments[$key];
			}
		} elseif ( ! empty( $atts['exclude'] ) ) {
			$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		} else {
			$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
		}
	
		if ( empty( $attachments ) ) {
			return '';
		}
	
		if ( is_feed() ) {
			$output = "\n";
			foreach ( $attachments as $att_id => $attachment ) {
				$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
			}
			return $output;
		}
		// /WP default
		
		$gallery_id = "nm-wp-gallery-{$instance}";
		$slider_settings_data = ' data-slides-to-show="' . intval( $atts['columns'] ) . '"';
		
		$output = "<div id='$gallery_id' class='nm-blog-slider slick-slider slick-controls-gray slick-dots-inside'" . $slider_settings_data . ">";
		
		foreach ( $attachments as $id => $attachment ) {
			$image_src = wp_get_attachment_image_src( $id, $atts['size'] );
			$output .= '<div><img src="' . esc_url( $image_src[0] ) . '" width="' . esc_attr( $image_src[1] ) . '" height="' . esc_attr( $image_src[2] ) . '" /></div>';
		}
				
		$output .= "</div>\n";
	
		return $output;
	}
		
	/* WP gallery: Set page include value */
	function nm_wp_gallery_set_include() {
		nm_add_page_include( 'wp-gallery' );
		
		return ''; // Returning an empty string will output the default WP gallery
	}
	
	if ( $nm_theme_options['custom_wp_gallery'] ) {
		add_filter( 'post_gallery', 'nm_wp_gallery', 10, 2 );
	} else {
		add_filter( 'post_gallery', 'nm_wp_gallery_set_include' );
	}
	
	
	
	/* Sidebars & Widgets
	=============================================================== */
	
	/* Register/include sidebars & widgets */
	function nm_widgets_init() {
		global $nm_globals, $nm_theme_options;
		
		// Sidebar: Default
		register_sidebar( array(
			'name' 				=> __( 'Sidebar', 'nm-framework' ),
			'id' 				=> 'sidebar',
			'before_widget'		=> '<div id="%1$s" class="widget %2$s">',
			'after_widget' 		=> '</div>',
			'before_title' 		=> '<h3 class="nm-widget-title">',
			'after_title' 		=> '</h3>'
		) );
		
		
		// Sidebar: Shop
		if ( $nm_globals['shop_filters_scrollbar'] ) {
			/*register_sidebar( array(
				'name' 				=> __( 'Shop', 'nm-framework' ),
				'id' 				=> 'widgets-shop',
				'before_widget'		=> '<li id="%1$s" class="scroll-enabled scroll-type-' . esc_attr( $nm_theme_options['shop_filters_scrollbar'] ) . ' widget %2$s">',
				'after_widget' 		=> '</div></div></li>',
				'before_title' 		=> '<h3 class="nm-widget-title">',
				'after_title' 		=> '</h3><div class="nm-shop-widget-col"><div class="nm-shop-widget-scroll">'
			));
			
			/* Sidebar: Shop - Add opening "div" wrapper to widgets with no title */
			/*function nm_shop_widgets_empty_title_fix( $params ) {
				// Make sure widget is in the "Shop" sidebar
				if ( $params[0]['id'] === 'widgets-shop' ) {
					global $wp_registered_widgets;
					
					// Get widget settings
					$settings_getter = $wp_registered_widgets[ $params[0]['widget_id'] ]['callback'][0];
					$settings = $settings_getter->get_settings();
					$settings = $settings[ $params[1]['number'] ];
					
					// Check if widget title is empty
					if ( isset( $settings['title'] ) && empty( $settings['title'] ) ) {	
						// Append opening wrapper element
						$params[0]['before_widget'] .= '<div class="nm-shop-widget-col"><div class="nm-shop-widget-scroll">';
					}
				}
				
				return $params;
			}
			add_filter( 'dynamic_sidebar_params', 'nm_shop_widgets_empty_title_fix' );*/
            register_sidebar( array(
				'name' 				=> __( 'Shop', 'nm-framework' ),
				'id' 				=> 'widgets-shop',
				'before_widget'		=> '<li id="%1$s" class="scroll-enabled scroll-type-default widget %2$s"><div class="nm-shop-widget-col">',
				'after_widget' 		=> '</div></div></li>',
				'before_title' 		=> '<h3 class="nm-widget-title">',
				'after_title' 		=> '</h3></div><div class="nm-shop-widget-col"><div class="nm-shop-widget-scroll">'
			));
		} else {
            register_sidebar( array(
				'name' 				=> __( 'Shop', 'nm-framework' ),
				'id' 				=> 'widgets-shop',
				'before_widget'		=> '<li id="%1$s" class="widget %2$s"><div class="nm-shop-widget-col">',
				'after_widget' 		=> '</div></li>',
				'before_title' 		=> '<h3 class="nm-widget-title">',
				'after_title' 		=> '</h3></div><div class="nm-shop-widget-col">'
			) );
		}
		
		
		// Sidebar: Footer
		register_sidebar( array(
			'name' 				=> __( 'Footer', 'nm-framework' ),
			'id' 				=> 'footer',
			'before_widget'		=> '<li id="%1$s" class="widget %2$s">',
			'after_widget' 		=> '</li>',
			'before_title' 		=> '<h3 class="nm-widget-title">',
			'after_title' 		=> '</h3>'
		) );
		
		
		// Sidebar: Visual Composer - Widgetised Sidebar
		register_sidebar( array(
			'name' 				=> __( 'Visual Composer - Widgetised Sidebar', 'nm-framework' ),
			'id' 				=> 'vc-sidebar',
			'before_widget'		=> '<div id="%1$s" class="widget %2$s">',
			'after_widget' 		=> '</div>',
			'before_title' 		=> '<h3 class="nm-widget-title">',
			'after_title' 		=> '</h3>'
		) );
		
		
		// Custom WooCommerce widgets
		// NOTE: The custom WooCommerce -filter- widgets will not work without the widget-id fix (see "nm_add_woocommerce_widget_ids()" below)
		if ( class_exists( 'WC_Widget' ) ) {
			// Product sorting
			include_once( NM_DIR . '/widgets/woocommerce-product-sorting.php' );
			register_widget( 'NM_WC_Widget_Product_Sorting' );
			
			// Price filter list
			include_once( NM_DIR . '/widgets/woocommerce-price-filter.php' );
			register_widget( 'NM_WC_Widget_Price_Filter' );
			
			// Color filter list
			include_once( NM_DIR . '/widgets/woocommerce-color-filter.php' );
			register_widget( 'WC_Widget_Color_Filter' );
		}
		
		
		// Unregister widgets
		unregister_widget( 'WC_Widget_Cart' );
		if ( ! defined( 'NM_ENABLE_PRICE_SLIDER' ) ) {
            unregister_widget( 'WC_Widget_Price_Filter' ); // Note: The price-slider doesn't work with Ajax currently (there's no JavaScript function available to re-init the price-slider)
        }
	}
	add_action( 'widgets_init', 'nm_widgets_init' ); // Register widget sidebars
	
	/* 
	 *	Add relevant WooCommerce widget-id's to "sidebars_widgets" option so the custom product filters will work
	 *
	 * 	Note: WooCommerce use "is_active_widget()" to check for active widgets in: "../includes/class-wc-query.php"
	 */
	function nm_add_woocommerce_widget_ids( $sidebars_widgets, $old_sidebars_widgets = array() ) {
		$shop_sidebar_id = 'widgets-shop';
		$shop_widgets = $sidebars_widgets[$shop_sidebar_id];
		
		if ( is_array( $shop_widgets ) ) {
			foreach ( $shop_widgets as $widget ) {
				$widget_id = _get_widget_id_base( $widget );
				
				if ( $widget_id === 'nm_woocommerce_price_filter' ) {
					$sidebars_widgets[$shop_sidebar_id][] = 'woocommerce_price_filter-12345';
				} else if ( $widget_id === 'nm_woocommerce_color_filter' ) {
					$sidebars_widgets[$shop_sidebar_id][] = 'woocommerce_layered_nav-12345';
				}
			}
		}
		
		return $sidebars_widgets;
	}
	add_action( 'pre_update_option_sidebars_widgets', 'nm_add_woocommerce_widget_ids' );
	
	/*function nm_check_sidebars_array() {
		global $sidebars_widgets;
		echo '<pre>';
		var_dump( $sidebars_widgets['widgets-shop'] );
		echo '</pre>';
	}
	add_action( 'init', 'nm_check_sidebars_array' );*/
	
	
	/* Page includes: Include element */
	function nm_include_page_includes_element() {
		global $nm_page_includes;
		
		$classes = '';
		
		foreach ( $nm_page_includes as $class => $value )
			$classes .= $class . ' ';
		
		echo '<div id="nm-page-includes" class="' . esc_attr( $classes ) . '" style="display:none;">&nbsp;</div>' . "\n\n";
	}
	add_action( 'wp_footer', 'nm_include_page_includes_element' ); // Include "page includes" element
	
	
	
	/* Contact Form 7
	=============================================================== */
	
	if ( isset( $nm_theme_options['cf7_pages'] ) && strlen( $nm_theme_options['cf7_pages'] ) > 0 ) {
		/* Include CF7 scripts on specified page(s) */
		function nm_cf7_scripts() {
			global $nm_theme_options;
			
			if ( is_page( array( $nm_theme_options['cf7_pages'] ) ) ) {
				// Enqueue CF7 scripts
				wpcf7_enqueue_scripts();
			}
		}
		add_action( 'wp_enqueue_scripts', 'nm_cf7_scripts' );
		
		
		// Disable default CF7 CSS and JavaScript (included on every page otherwise)
		add_filter( 'wpcf7_load_css', '__return_false' );
		add_filter( 'wpcf7_load_js', '__return_false' );
	}
	
	
	
	/* Actions & Filters
	=============================================================== */
	
	// Add Filters
	add_filter( 'widget_text', 'do_shortcode' ); 					// Allow shortcodes in text-widgets
	add_filter( 'widget_text', 'shortcode_unautop' ); 				// Disable auto-formatting (line breaks) in text-widgets
	add_filter( 'the_excerpt', 'shortcode_unautop' ); 				// Remove auto <p> tags in Excerpt (Manual Excerpts only)
	//add_filter( 'the_excerpt', 'do_shortcode' ); 					// Allow shortcodes in excerpts
	add_filter( 'use_default_gallery_style', '__return_false' );	// Remove default inline WP gallery styles
	
	
	/*============================ by zhaolx  =================================================*/	
	function wp_rename_upload_file($filename){
		$file_info = pathinfo($filename);
		$parts = explode('.', $filename);
		$ext = array_pop($parts);
		//$name = basename($filename,$ext);
		//return substr(md5($name),0,15).$ext;
		return date('Ymd_His').rand(11111,9999999).'.'.$ext;
	}
	add_filter('sanitize_file_name','wp_rename_upload_file',10);
	//add to cart button
	function woocommerce_loop_add_to_cart() {
		
		global $product;
		$icon = apply_filters( 'nm_cartlist_button_icon', '<i class="nm-menu-cart-icon nm-font nm-font-shopping-cart"></i>' );
		$output = sprintf( '<a rel="nofollow" href="%s" data-product_id="%s" data-quantity="1" data-product_sku="%s" class="add_to_cart_button ajax_add_to_cart"  title="%s">%s</a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( $product->id ),
			esc_attr( $product->get_sku() ),
			esc_html__( 'Add to Cart', 'nm-wishlist' ),
			$icon
		);
		
		echo $output;
		
	}
	//get wishlist count
	function nm_get_wish_count(){
		global $nm_wishlist_ids;

		if ( ! empty( $nm_wishlist_ids ) ) {
			$wishlist_ids = array_keys( $nm_wishlist_ids );
			$args = array(
				'post_type'		 => 'product',
				'post__in'		 => $wishlist_ids
			);
			
			$wishlist_loop = new WP_Query( $args );			
			return (int)$wishlist_loop->post_count;
		}
	}
	// Hide prices if not login
	add_filter('woocommerce_get_price_html', 'members_show_price');
    function members_show_price($price){
		if(is_user_logged_in() ){
			return $price;
		}else{
			/* remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 ); */
			return '<a href="' . get_permalink(woocommerce_get_page_id('myaccount')) . '">'._e( 'Login to see the price', 'woocommerce' ).'</a>';
		}
    }
	//rewirte myaccount link function
	function get_myaccount_link( $is_header = true ) {
		global $nm_theme_options;
		
		$myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
		
		// Link title/icon
		if ( $is_header && $nm_theme_options['menu_login_icon'] ) {
			//$icon_class = apply_filters( 'nm_login_icon_class', 'nm-font nm-font-user' );
			//$link_title = sprintf( '<i class="nm-myaccount-icon %s"></i>', $icon_class );
            $link_title = apply_filters( 'nm_myaccount_icon', '<i class="nm-myaccount-icon nm-font nm-font-user"></i>', 'nm-font nm-font-user' );
		}else{
			if(is_user_logged_in()){
				$link_title = esc_html__( 'My Account', 'nm-framework' );
			}else{
				$link_title = esc_html__( 'Register | Sign In', 'woocommerce' );
			}
			
		}
		
		return '<a href="' . esc_url( $myaccount_url ) . '" id="nm-menu-account-btn">' . apply_filters( 'nm_myaccount_title', $link_title ) . '</a>';
	}
	//add_action('woocommerce_before_shop_loop_item','members_show_wish_cart') ; 
	function members_show_wish_cart(){
		global $nm_globals;
		if(!is_user_logged_in() ){
			$nm_globals['wishlist_enabled'] = false;
		}
	}
	// this is just to prevent the user log in automatically after register
	function wc_registration_redirect( $redirect_to ) {
			wp_logout();
			$myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
			wp_redirect( $myaccount_url.'/?newuser=');
			exit;
	}
	// when user login, we will check whether this guy email is verify
	function wp_authenticate_user( $userdata ) {
			$isActivated = get_user_meta($userdata->ID, 'is_activated', true);
			$myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
			if ( !$isActivated ) {
					$userdata = new WP_Error(
									'inkfool_confirmation_error',
									__( '<strong>ERROR:</strong> Your account has to be activated before you can login. You can resend by clicking <a href="'.$myaccount_url.'/?userid='.$userdata->ID.'&resend">here</a>', 'inkfool' )
									);
			}
			return $userdata;
	}
	// when a user register we need to send them an email to verify their account
	function my_user_register($user_id) {
			// get user data
			$user_info = get_userdata($user_id);
			// create md5 code to verify later
			$code = md5(time());
			// make it into a code to send it to user via email
			$string = array('id'=>$user_id, 'code'=>$code);
			// create the activation code and activation status
			update_user_meta($user_id, 'is_activated', 0);
			update_user_meta($user_id, 'activationcode', $code);
			$myaccount_url = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
			// create the url
			$url = $myaccount_url. '/?activekey=' .base64_encode( serialize($string));
			// basically we will edit here to make this nicer
			$html = 'Please click the following links <br/><br/> <a href="'.$url.'">'.$url.'</a>';
			// send an email out to user
			//echo $user_info->user_email;exit;
			wc_mail($user_info->user_email, __('Please activate your account'), $html);
	}
	// we need this to handle all the getty hacks i made
	function my_init(){
			// check whether we get the activation message
			if(isset($_GET['activekey'])){
					$data = unserialize(base64_decode($_GET['activekey']));
					$code = get_user_meta($data['id'], 'activationcode', true);
					// check whether the code given is the same as ours
					if($code == $data['code']){
							// update the db on the activation process
							update_user_meta($data['id'], 'is_activated', 1);
							wc_add_notice( __( '<strong>Success:</strong> Your account has been activated! ', 'inkfool' )  );
					}else{
							wc_add_notice( __( '<strong>Error:</strong> Activation fails, please contact our administrator. ', 'inkfool' )  );
					}
			}
			if(isset($_GET['newuser'])){
					wc_add_notice( __( '<strong>Error:</strong> We have send a email to your registe email to be activate your account. Please check your email.', 'inkfool' ) );
			}
			if(isset($_GET['userid']) && isset($_GET['resend']) && !isset($_POST['username'])){
					my_user_register($_GET['userid']);
					wc_add_notice( __( '<strong>Succes:</strong> Your activation email has been resend. Please check your email.', 'inkfool' ) );
			}
	}
	// hooks handler
	add_action( 'init', 'my_init' );
	add_filter('woocommerce_registration_redirect', 'wc_registration_redirect');
	add_filter('wp_authenticate_user', 'wp_authenticate_user',10,2);
	add_action('user_register', 'my_user_register',10,2);