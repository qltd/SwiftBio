/* global wp, wpforms_admin, jconfirm, wpCookies, Choices */

;(function($) {

	'use strict';

	// Global settings access.
	var s;

	// Admin object.
	var WPFormsAdmin = {

		// Settings.
		settings: {
			iconActivate: '<i class="fa fa-toggle-on fa-flip-horizontal" aria-hidden="true"></i>',
			iconDeactivate: '<i class="fa fa-toggle-on" aria-hidden="true"></i>',
			iconInstall: '<i class="fa fa-cloud-download" aria-hidden="true"></i>',
			iconSpinner: '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i>',
			mediaFrame: false
		},

		/**
		 * Start the engine.
		 *
		 * @since 1.3.9
		 */
		init: function() {

			// Settings shortcut.
			s = this.settings;

			// Document ready.
			$( document ).ready( WPFormsAdmin.ready );

			// Forms Overview.
			WPFormsAdmin.initFormOverview();

			// Entries Single (Details).
			WPFormsAdmin.initEntriesSingle();

			// Entries List.
			WPFormsAdmin.initEntriesList();

			// Welcome activation.
			WPFormsAdmin.initWelcome();

			// Addons List.
			WPFormsAdmin.initAddons();

			// Settings.
			WPFormsAdmin.initSettings();
		},

		/**
		 * Document ready.
		 *
		 * @since 1.3.9
		 */
		ready: function() {

			// To prevent jumping (since WP core moves the notices with js),
			// they are hidden initally with CSS, then revealed below with JS,
			// which runs after they have been moved.
			$( '.notice' ).show();

			// If there are screen options we have to move them.
			$( '#screen-meta-links, #screen-meta' ).prependTo( '#wpforms-header-temp' ).show();

			// Init fancy selects via choices.js.
			$( '.choicesjs-select' ).each( function() {
				var $this = $( this ),
					args  = { searchEnabled: false };
				if ( $this.attr( 'multiple' ) ) {
					args.searchEnabled = true;
					args.removeItemButton = true;
				}
				if ( $this.data( 'placeholder' ) ) {
					args.placeholderValue = $this.data( 'placeholder' );
				}
				if ( $this.data( 'search' ) ) {
					args.searchEnabled = true;
				}
				new Choices( $this[0], args );
			});

			// Init colorpickers via minicolors.js.
			$( '.wpforms-color-picker' ).minicolors();

			// Init fancy File Uploads.
			$( '.wpforms-file-upload' ).each( function(){
				var $input	 = $( this ).find( 'input[type=file]' ),
					$label	 = $( this ).find( 'label' ),
					labelVal = $label.html();
				$input.on( 'change', function( event ) {
					var fileName = '';
					if ( this.files && this.files.length > 1 ) {
						fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
					} else if( event.target.value ) {
						fileName = event.target.value.split( '\\' ).pop();
					}
					if ( fileName ) {
						$label.find( '.fld' ).html( fileName );
					} else {
						$label.html( labelVal );
					}
				});
				// Firefox bug fix.
				$input.on( 'focus', function(){ $input.addClass( 'has-focus' ); }).on( 'blur', function(){ $input.removeClass( 'has-focus' ); });
			});

			// jquery-confirm defaults.
			jconfirm.defaults = {
				closeIcon: true,
				backgroundDismiss: true,
				escapeKey: true,
				animationBounce: 1,
				useBootstrap: false,
				theme: 'modern',
				boxWidth: '400px'
			};

			// Action available for each binding.
			$( document ).trigger( 'wpformsReady' );
		},

		//--------------------------------------------------------------------//
		// Forms Overview
		//--------------------------------------------------------------------//

		/**
		 * Element bindings for Form Overview page.
		 *
		 * @since 1.3.9
		 */
		initFormOverview: function() {

			// Confirm form entry deletion and duplications.
			$( document ).on( 'click', '#wpforms-overview .wp-list-table .delete a, #wpforms-overview .wp-list-table .duplicate a', function( event ) {

				event.preventDefault();

				var url = $( this ).attr( 'href' ),
					msg = $( this ).parent().hasClass( 'delete' ) ? wpforms_admin.form_delete_confirm : wpforms_admin.form_duplicate_confirm;

				// Trigger alert modal to confirm.
				$.confirm({
					title: false,
					content: msg,
					backgroundDismiss: false,
					closeIcon: false,
					icon: 'fa fa-exclamation-circle',
					type: 'orange',
					buttons: {
						confirm: {
							text: wpforms_admin.ok,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ],
							action: function(){
								window.location = url;
							}
						},
						cancel: {
							text: wpforms_admin.cancel,
							keys: [ 'esc' ],
						}
					}
				});
			});
		},

		//--------------------------------------------------------------------//
		// Entry Single (Details)
		//--------------------------------------------------------------------//

		/**
		 * Element bindings for Entries Single (Details) page.
		 *
		 * @since 1.3.9
		 */
		initEntriesSingle: function() {

			// Entry navigation hotkeys.
			// We only want to listen on the applicable admin page.
			if ( 'wpforms-entries' === WPFormsAdmin.getQueryString( 'page' ) && 'details' === WPFormsAdmin.getQueryString( 'view' ) ) {
				WPFormsAdmin.entryHotkeys();
			}

			// Confirm entry deletion.
			$( document ).on( 'click', '#wpforms-entries-single .submitdelete', function( event ) {

				event.preventDefault();

				var url = $( this ).attr( 'href' );

				// Trigger alert modal to confirm.
				$.confirm({
					title: false,
					content: wpforms_admin.entry_delete_confirm,
					backgroundDismiss: false,
					closeIcon: false,
					icon: 'fa fa-exclamation-circle',
					type: 'orange',
					buttons: {
						confirm: {
							text: wpforms_admin.ok,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ],
							action: function(){
								window.location = url;
							}
						},
						cancel: {
							text: wpforms_admin.cancel,
							keys: [ 'esc' ],
						}
					}
				});
			});

			// Open Print preview in new window.
			$( document ).on( 'click', '#wpforms-entries-single .wpforms-entry-print a', function( event ) {

				event.preventDefault();

				window.open( $( this ).attr( 'href' ) );
			});

			// Toggle displaying empty fields.
			$( document ).on( 'click', '#wpforms-entries-single .wpforms-empty-field-toggle', function( event ) {

				event.preventDefault();

				// Handle cookie.
				if ( wpCookies.get( 'wpforms_entry_hide_empty' ) == 'true') {

					// User was hiding empty fields, so now display them.
					wpCookies.remove('wpforms_entry_hide_empty');
					$( this ).text( wpforms_admin.entry_empty_fields_hide );
				} else {

					// User was seeing empty fields, so now hide them.
					wpCookies.set( 'wpforms_entry_hide_empty', 'true', 2592000 ); // 1month.
					$( this ).text( wpforms_admin.entry_empty_fields_show );
				}

				$( '.wpforms-entry-field.empty' ).toggle();
			});

			// Display notes editor.
			$( document ).on( 'click', '#wpforms-entries-single .wpforms-entry-notes-new .add', function( event ) {

				event.preventDefault();

				$( this ).hide().next( 'form' ).slideToggle();
			});

			// Cancel note.
			$( document ).on( 'click', '#wpforms-entries-single .wpforms-entry-notes-new .cancel', function( event ) {

				event.preventDefault();

				$( this ).closest( 'form' ).slideToggle();
				$('.wpforms-entry-notes-new .add').show();
			});

			// Delete note.
			$( document ).on( 'click', '#wpforms-entries-single .wpforms-entry-notes-byline .note-delete', function( event ) {

				event.preventDefault();

				var url = $( this ).attr( 'href' );

				// Trigger alert modal to confirm.
				$.confirm({
					title: false,
					content: wpforms_admin.entry_note_delete_confirm,
					backgroundDismiss: false,
					closeIcon: false,
					icon: 'fa fa-exclamation-circle',
					type: 'orange',
					buttons: {
						confirm: {
							text: wpforms_admin.ok,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ],
							action: function(){
								window.location = url;
							}
						},
						cancel: {
							text: wpforms_admin.cancel,
							keys: [ 'esc' ],
						}
					}
				});
			});
		},

		/**
		 * Hotkeys for Entries Single (Details) page.
		 *
		 * j triggers previous entry, k triggers next entry.
		 *
		 * @since 1.4.0
		 */
		 entryHotkeys: function() {

			$( document ).keydown( function( event ) {
				if ( 74 === event.keyCode && ! WPFormsAdmin.isFormTypeNode( event.target.nodeName ) ) {
					// j key has been pressed outside of a form element, go to
					// the previous entry.
					var prevEntry = $('#wpforms-entry-prev-link').attr( 'href' );
					if ( '#' !== prevEntry ) {
						window.location.href = prevEntry;
					}
				} else if ( 75 === event.keyCode && ! WPFormsAdmin.isFormTypeNode( event.target.nodeName ) ) {
					// k key has been pressed outside of a form element, go to
					// the previous entry.
					var nextEntry = $('#wpforms-entry-next-link').attr( 'href' );
					if ( '#' !== nextEntry ) {
						window.location.href = nextEntry;
					}
				}
			});
		 },


		//--------------------------------------------------------------------//
		// Entry List
		//--------------------------------------------------------------------//

		/**
		 * Element bindings for Entries List table page.
		 *
		 * @since 1.3.9
		 */
		initEntriesList: function() {

			$( document ).on( 'click', '#wpforms-entries-table-edit-columns', function( event ) {

				event.preventDefault();

				WPFormsAdmin.entriesListFieldColumn();
			});

			// Toogle form selector dropdown.
			$( document ).on( 'click', '#wpforms-entries-list .form-selector .toggle', function( event ) {

				event.preventDefault();

				$( this ).toggleClass( 'active' ).next( '.form-list' ).toggle();

			});

			// Confirm entry deletion.
			$( document ).on( 'click', '#wpforms-entries-list .wp-list-table .delete', function( event ) {

				event.preventDefault();

				var url = $( this ).attr( 'href' );

				// Trigger alert modal to confirm.
				$.confirm({
					title: false,
					content: wpforms_admin.entry_delete_confirm,
					backgroundDismiss: false,
					closeIcon: false,
					icon: 'fa fa-exclamation-circle',
					type: 'orange',
					buttons: {
						confirm: {
							text: wpforms_admin.ok,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ],
							action: function(){
								window.location = url;
							}
						},
						cancel: {
							text: wpforms_admin.cancel,
							keys: [ 'esc' ],
						}
					}
				});
			});

			// Toggle entry stars.
			$( document ).on( 'click', '#wpforms-entries-list .wp-list-table .indicator-star', function( event ) {

				event.preventDefault();

				var $this = $( this ),
					task  = '',
					total = Number( $( '#wpforms-entries-list .starred-num' ).text() ),
					id    = $this.data( 'id' );

				if ( $this.hasClass( 'star' ) ) {
					task = 'star';
					total++;
					$this.attr( 'title', wpforms_admin.entry_unstar );
				} else {
					task = 'unstar';
					total--;
					$this.attr( 'title', wpforms_admin.entry_star );
				}
				$this.toggleClass( 'star unstar' );
				$( '#wpforms-entries-list .starred-num' ).text( total );

				var data = {
					task    : task,
					action  : 'wpforms_entry_list_star',
					nonce   : wpforms_admin.nonce,
					entry_id: id
				}
				$.post( wpforms_admin.ajax_url, data );
			});

			// Toggle entry read state.
			$( document ).on( 'click', '#wpforms-entries-list .wp-list-table .indicator-read', function( event ) {

				event.preventDefault();

				var $this = $( this ),
					task  = '',
					total = Number( $( '#wpforms-entries-list .unread-num' ).text() ),
					id    = $this.data( 'id' );

				if ( $this.hasClass( 'read' ) ) {
					task = 'read';
					total--;
					$this.attr( 'title', wpforms_admin.entry_unread );
				} else {
					task = 'unread';
					total++;
					$this.attr( 'title', wpforms_admin.entry_read );
				}
				$this.toggleClass( 'read unread' );
				$( '#wpforms-entries-list .unread-num' ).text( total );

				var data = {
					task    : task,
					action  : 'wpforms_entry_list_read',
					nonce   : wpforms_admin.nonce,
					entry_id: id
				}
				$.post( wpforms_admin.ajax_url, data );
			});

			// Confirm mass entry deletion - this deletes ALL entries.
			$( document ).on( 'click', '#wpforms-entries-list .form-details-actions-deleteall', function( event ) {

				event.preventDefault();

				var url = $( this ).attr( 'href' );

				// Trigger alert modal to confirm.
				$.confirm({
					title: wpforms_admin.heads_up,
					content: wpforms_admin.entry_delete_all_confirm,
					backgroundDismiss: false,
					closeIcon: false,
					icon: 'fa fa-exclamation-circle',
					type: 'orange',
					buttons: {
						confirm: {
							text: wpforms_admin.ok,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ],
							action: function(){
								window.location = url;
							}
						},
						cancel: {
							text: wpforms_admin.cancel,
							keys: [ 'esc' ],
						}
					}
				});
			});
		},

		/**
		 * Display settings to change the entry list field columns/
		 *
		 * @since 1.4.0
		 */
		entriesListFieldColumn: function() {

			$.alert({
				title: wpforms_admin.entry_field_columns,
				boxWidth: '500px',
				content: s.iconSpinner + $( '#wpforms-field-column-select' ).html(),
				onContentReady: function() {

					var $modalContent = this.$content,
						$select       = $modalContent.find( 'select' ),
						choices       = new Choices( $select[0], {
							maxItemCount: 5,
							placeholderValue: wpforms_admin.fields_select+'...',
							removeItemButton: true,
							shouldSort: false,
							callbackOnInit: function() {
								$modalContent.find( '.fa' ).remove();
								$modalContent.find( 'form' ).show();
							}
						});

					$( '.jconfirm-content-pane, .jconfirm-box' ).css( 'overflow','visible' );

					choices.passedElement.addEventListener( 'change', function( event ) {
						choices.hideDropdown();
					}, false );
				},
				buttons: {
					confirm: {
						text: wpforms_admin.save_refresh,
						btnClass: 'btn-confirm',
						keys: ['enter'],
						action: function() {
							this.$content.find( 'form' ).submit();
						}
					},
					cancel: {
						text: wpforms_admin.cancel,
						keys: [ 'esc' ],
					}
				}
			});
		},

		//--------------------------------------------------------------------//
		// Welcome Activation.
		//--------------------------------------------------------------------//

		/**
		 * Welcome activation page.
		 *
		 * @since 1.3.9
		 */
		initWelcome: function() {

			// Open modal and play How To video.
			$( document ).on( 'click', '#wpforms-welcome .play-video', function( event ) {

				event.preventDefault();

				var video = '<div class="video-container"><iframe width="1280" height="720" src="https://www.youtube-nocookie.com/embed/yDyvSGV7tP4?rel=0&amp;showinfo=0&amp;autoplay=1" frameborder="0" allowfullscreen></iframe></div>';

				$.dialog({
					title: false,
					content: video,
					closeIcon: true,
					boxWidth: '70%'
				});
			});
		},

		//--------------------------------------------------------------------//
		// Addons List.
		//--------------------------------------------------------------------//

		/**
		 * Element bindings for Addons List page.
		 *
		 * @since 1.3.9
		 */
		initAddons: function() {

			// Display all addon boxes as the same height.
			$( document ).on( 'wpformsReady', function() {

				// Only run on the addons page because the matchHeight jQuery
				// library is not loaded globally.
				if ( $( '#wpforms-admin-addons' ).length ) {
					$( '.addon-item .details' ).matchHeight( { byrow: false, property: 'min-height' } );
				}
			});

			// Toogle an addon state.
			$( document ).on( 'click', '#wpforms-admin-addons .addon-item button', function( event ) {

				event.preventDefault();

				WPFormsAdmin.addonToggle( $( this ) );
			});
		},

		/**
		 * Toggle addon state.
		 *
		 * @since 1.3.9
		 */
		addonToggle: function( el ) {

			var $this  = $( el ),
				$addon = $this.closest( '.addon-item' ),
				plugin = $this.attr( 'data-plugin' ),
				action,
				cssClass,
				statusText,
				buttonText,
				errorText,
				successText;

			$this.prop( 'disabled', true ).addClass( 'loading' );
			$this.html( s.iconSpinner );

			if ( $this.hasClass( 'status-active' ) ) {
				// Deactivate.
				action     = 'wpforms_deactivate_addon';
				cssClass   = 'status-inactive';
				statusText = wpforms_admin.addon_inactive;
				buttonText = s.iconActivate + wpforms_admin.addon_activate;
				errorText  = s.iconDeactivate + wpforms_admin.addon_deactivate;

			} else if ( $this.hasClass( 'status-inactive' ) ) {
				// Activate.
				action     = 'wpforms_activate_addon';
				cssClass   = 'status-active';
				statusText = wpforms_admin.addon_active;
				buttonText = s.iconDeactivate + wpforms_admin.addon_deactivate;
				errorText  = s.iconActivate + wpforms_admin.addon_activate;

			} else if ( $this.hasClass( 'status-download' ) ) {
				// Install.
				action     = 'wpforms_install_addon';
				cssClass   = 'status-inactive';
				statusText = wpforms_admin.addon_inactive;
				buttonText = s.iconActivate + wpforms_admin.addon_activate;
				errorText  = s.iconInstall + wpforms_admin.addon_install;
			} else {
				return;
			}

			var data = {
				action: action,
				nonce : wpforms_admin.nonce,
				plugin: plugin
			}
			$.post( wpforms_admin.ajax_url, data, function( res ) {

				if ( res.success ){
					if ( 'wpforms_install_addon' === action ) {
						$this.attr( 'data-plugin', res.data.basename );
						var successText = res.data.msg;
					} else {
						var successText = res.data;
					}
					$addon.find( '.actions' ).append( '<div class="msg success">'+successText+'</div>' );
					$addon.find( 'span.status-label' ).removeClass( 'status-active status-inactive status-download' ).addClass( cssClass ).text( statusText );
					$this.removeClass( 'status-active status-inactive status-download' ).addClass( cssClass ).html( buttonText );
				} else {
					$addon.find( '.actions' ).append( '<div class="msg error">'+res.data+'</div>' );
					$this.html( errorText );
				}

				$this.prop( 'disabled', false ).removeClass( 'loading' );

				// Automatically clear addon messages after 3 seconds.
				setTimeout( function() {
					$( '.addon-item .msg' ).remove();
				}, 3000 );

			}).fail( function( xhr ) {
				console.log( xhr.responseText );
			});
		},

		//--------------------------------------------------------------------//
		// Settings.
		//--------------------------------------------------------------------//

		/**
		 * Element bindings for Settings page.
		 *
		 * @since 1.3.9
		 */
		initSettings: function() {

			// Watch for hashes and scroll to if found.
			// Display all addon boxes as the same height.
			$( document ).on( 'wpformsReady', function() {

				// Only proceed if we're on the settings page.
				if ( ! $( '#wpforms-settings' ).length ) {
					return;
				}

				var integrationFocus = WPFormsAdmin.getQueryString( 'wpforms-integration' ),
					jumpTo           = WPFormsAdmin.getQueryString( 'jump' );

				if ( integrationFocus ) {
					$( 'body' ).animate({
						scrollTop: $( '#wpforms-integration-'+integrationFocus ).offset().top
					}, 1000 );
				} else if ( jumpTo ) {
					$( 'body' ).animate({
						scrollTop: $( '#'+jumpTo ).offset().top
					}, 1000 );
				}
			});

			// Image upload fields.
			$( document ).on( 'click', '.wpforms-setting-row-image button', function( event ) {

				event.preventDefault();

				WPFormsAdmin.imageUploadModal( $( this ) );
			});

			// Verify license key.
			$( document ).on( 'click', '#wpforms-setting-license-key-verify', function( event ) {

				event.preventDefault();

				WPFormsAdmin.licenseVerify( $( this ) );
			});

			// Deactivate license key.
			$( document ).on( 'click', '#wpforms-setting-license-key-deactivate', function( event ) {

				event.preventDefault();

				WPFormsAdmin.licenseDeactivate( $( this ) );
			});

			// Refresh license key.
			$( document ).on( 'click', '#wpforms-setting-license-key-refresh', function( event ) {

				event.preventDefault();

				WPFormsAdmin.licenseRefresh( $( this ) );
			});

			/**
			 * @todo Refactor providers settings tab. Code below is legacy.
			 */

			// Integration connect.
			$( document ).on( 'click', '.wpforms-settings-provider-connect', function( event ) {

				event.preventDefault();

				WPFormsAdmin.integrationConnect( $( this ) );
			});

			// Integration account disconnect.
			$( document ).on( 'click', '.wpforms-settings-provider-accounts-list a', function( event ) {

				event.preventDefault();

				WPFormsAdmin.integrationDisconnect( $( this ) );
			});

			// Integration individual display toggling.
			$( document ).on( 'click', '.wpforms-settings-provider-header', function( event ) {

				event.preventDefault();

				$( this ).parent().find( '.wpforms-settings-provider-accounts' ).slideToggle();
				$( this ).parent().find( '.wpforms-settings-provider-logo i' ).toggleClass( 'fa-chevron-right fa-chevron-down' );
			});

			// Integration accounts display toggling.
			$( document ).on( 'click', '.wpforms-settings-provider-accounts-toggle a', function( event ) {

				event.preventDefault();

				var $connectFields = $( this ).parent().next( '.wpforms-settings-provider-accounts-connect' );
				$connectFields.find( 'input[type=text], input[type=password]' ).val('');
				$connectFields.slideToggle();
			});
		},

		/**
		 * Image upload modal window.
		 *
		 * @since 1.3.0
		 */
		imageUploadModal: function( el ) {

			 if ( s.media_frame ) {
				 s.media_frame.open();
				 return;
			 }

			 var $setting = $( el ).closest( '.wpforms-setting-field' );

			 s.media_frame = wp.media.frames.wpforms_media_frame = wp.media({
				 className: 'media-frame wpforms-media-frame',
				 frame: 'select',
				 multiple: false,
				 title: wpforms_admin.upload_image_title,
				 library: {
					 type: 'image'
				 },
				 button: {
					 text: wpforms_admin.upload_image_button
				 }
			 });

			 s.media_frame.on( 'select', function(){
				 // Grab our attachment selection and construct a JSON representation of the model.
				 var media_attachment = s.media_frame.state().get( 'selection' ).first().toJSON();

				 // Send the attachment URL to our custom input field via jQuery.
				 $setting.find( 'input[type=text]' ).val( media_attachment.url );
				 $setting.find( 'img' ).remove();
				 $setting.prepend( '<img src="'+media_attachment.url+'">' );
			 });

			 // Now that everything has been set, let's open up the frame.
			 s.media_frame.open();
		},

		/**
		 * Verify a license key.
		 *
		 * @since 1.3.9
		 */
		licenseVerify: function( el ) {

			var $this       = $( el ),
				$row        = $this.closest( '.wpforms-setting-row' ),
				buttonWidth = $this.outerWidth(),
				buttonLabel = $this.text(),
				data        = {
					action: 'wpforms_verify_license',
					nonce:   wpforms_admin.nonce,
					license: $('#wpforms-setting-license-key').val()
				};

			$this.html( s.iconSpinner ).css( 'width', buttonWidth ).prop( 'disabled', true );

			$.post( wpforms_admin.ajax_url, data, function( res ) {

				var icon  = 'fa fa-check-circle',
					color = 'green',
					msg;

				if ( res.success ){
					msg = res.data.msg;
					$row.find( '.type, .desc, #wpforms-setting-license-key-deactivate' ).show();
					$row.find( '.type strong' ).text( res.data.type );
					$('.wpforms-license-notice').remove();
				} else {
					icon  = 'fa fa-exclamation-circle';
					color = 'orange';
					msg   = res.data;
					$row.find( '.type, .desc, #wpforms-setting-license-key-deactivate' ).hide();
				}

				$.alert({
					title: false,
					content: msg,
					icon: icon,
					type: color,
					buttons: {
						confirm: {
							text: wpforms_admin.ok,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ]
						}
					}
				});

				$this.html( buttonLabel ).css( 'width', 'auto' ).prop( 'disabled', false );

			}).fail( function( xhr ) {
				console.log( xhr.responseText );
			});
		},

		/**
		 * Verify a license key.
		 *
		 * @since 1.3.9
		 */
		licenseDeactivate: function( el ) {

			var $this       = $( el ),
				$row        = $this.closest( '.wpforms-setting-row' ),
				buttonWidth = $this.outerWidth(),
				buttonLabel = $this.text(),
				data        = {
					action: 'wpforms_deactivate_license',
					nonce:   wpforms_admin.nonce
				};

			$this.html( s.iconSpinner ).css( 'width', buttonWidth ).prop( 'disabled', true );

			$.post( wpforms_admin.ajax_url, data, function( res ) {

				var icon  = 'fa fa-info-circle',
					color = 'blue',
					msg   = res.data;

				if ( res.success ){
					$row.find( '#wpforms-setting-license-key' ).val('');
					$row.find( '.type, .desc, #wpforms-setting-license-key-deactivate' ).hide();
				} else {
					icon  = 'fa fa-exclamation-circle';
					color = 'orange';
				}

				$.alert({
					title: false,
					content: msg,
					icon: icon,
					type: color,
					buttons: {
						confirm: {
							text: wpforms_admin.ok,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ]
						}
					}
				});

				$this.html( buttonLabel ).css( 'width', 'auto' ).prop( 'disabled', false );

			}).fail( function( xhr ) {
				console.log( xhr.responseText );
			});
		},

		/**
		 * Refresh a license key.
		 *
		 * @since 1.3.9
		 */
		licenseRefresh: function( el ) {

			var $this       = $( el ),
				$row        = $this.closest( '.wpforms-setting-row' ),
				data        = {
					action: 'wpforms_refresh_license',
					nonce:   wpforms_admin.nonce,
					license: $('#wpforms-setting-license-key').val()
				};

			$.post( wpforms_admin.ajax_url, data, function( res ) {

				var icon  = 'fa fa-check-circle',
					color = 'green',
					msg;

				if ( res.success ){
					msg = res.data.msg;
					$row.find( '.type strong' ).text( res.data.type );
				} else {
					icon  = 'fa fa-exclamation-circle';
					color = 'orange';
					msg   = res.data;
					$row.find( '.type, .desc, #wpforms-setting-license-key-deactivate' ).hide();
				}

				$.alert({
					title: false,
					content: msg,
					icon: icon,
					type: color,
					buttons: {
						confirm: {
							text: wpforms_admin.ok,
							btnClass: 'btn-confirm',
							keys: [ 'enter' ]
						}
					}
				});

			}).fail( function( xhr ) {
				console.log( xhr.responseText );
			});
		},

		/**
		 * Connect integration provider account.
		 *
		 * @since 1.3.9
		 */
		integrationConnect: function( el ) {

			var $this       = $( el ),
				buttonWidth = $this.outerWidth(),
				buttonLabel = $this.text(),
				$provider   = $this.closest( '.wpforms-settings-provider' ),
				data        = {
					action  : 'wpforms_settings_provider_add',
					data    : $this.closest( 'form' ).serialize(),
					provider: $this.data( 'provider' ),
					nonce   : wpforms_admin.nonce
				};

			$this.html( 'Connecting...' ).css( 'width', buttonWidth ).prop( 'disabled', true );

			$.post( wpforms_admin.ajax_url, data, function( res ) {

				if ( res.success ){
					$provider.find( '.wpforms-settings-provider-accounts-list ul' ).append( res.data.html );
					$provider.addClass( 'connected' );
					$this.closest( '.wpforms-settings-provider-accounts-connect' ).slideToggle();
				} else {
					var msg = wpforms_admin.provider_auth_error;
					if ( res.data.error_msg ) {
						msg += "\n"+res.data.error_msg;
					}
					$.alert({
						title: false,
						content: msg,
						icon: 'fa fa-exclamation-circle',
						type: 'orange',
						buttons: {
							confirm: {
								text: wpforms_admin.ok,
								btnClass: 'btn-confirm',
								keys: [ 'enter' ]
							}
						}
					});
					console.log(res);
				}

				$this.html( buttonLabel ).css( 'width', 'auto' ).prop( 'disabled', false );

			}).fail( function( xhr ) {
				console.log( xhr.responseText );
			});
		},

		/**
		 * Remove integration provider account.
		 *
		 * @since 1.3.9
		 */
		integrationDisconnect: function( el ) {

			var $this = $( el ),
				data = {
					action  : 'wpforms_settings_provider_disconnect',
					provider: $this.data( 'provider' ),
					key     : $this.data( 'key'),
					nonce   : wpforms_admin.nonce
				};

			$.confirm({
				title: wpforms_admin.heads_up,
				content: wpforms_admin.provider_delete_confirm,
				backgroundDismiss: false,
				closeIcon: false,
				icon: 'fa fa-exclamation-circle',
				type: 'orange',
				buttons: {
					confirm: {
						text: wpforms_admin.ok,
						btnClass: 'btn-confirm',
						keys: [ 'enter' ],
						action: function(){
							$.post( wpforms_admin.ajax_url, data, function( res ) {
								if ( res.success ){
									$this.parent().remove();
								} else {
									console.log( res );
								}
							}).fail( function( xhr ) {
								console.log( xhr.responseText );
							});
						}
					},
					cancel: {
						text: wpforms_admin.cancel,
						keys: [ 'esc' ],
					}
				}
			});
		},

		/**
		 * Return if the target nodeName is a form element.
		 *
		 * @since 1.4.0
		 */
		isFormTypeNode: function( name ) {

			name = name || false;

			if ( 'TEXTAREA' === name || 'INPUT' === name || 'SELECT' === name ){
				return true;
			}

			return false;
		},

		/**
		 * Get query string in a URL.
		 *
		 * @since 1.3.9
		 */
		getQueryString: function( name ) {

			var match = new RegExp( '[?&]' + name + '=([^&]*)' ).exec( window.location.search );
			return match && decodeURIComponent( match[1].replace(/\+/g, ' ') );
		},
	}

	WPFormsAdmin.init();

})( jQuery );
