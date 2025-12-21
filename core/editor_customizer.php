<?php
namespace Cora_Builder\Core;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Editor_Customizer {

	public function __construct() {
		add_action( 'elementor/editor/footer', [ $this, 'render_cora_panel' ] );
	}

	public function render_cora_panel() {
		?>
		<script>
		jQuery(document).ready(function($) {

			// --- CONFIG ---
			const CATEGORY_MAP = {
				'cora_cat_hero': 'Heroes & Headers',
				'cora_cat_cards': 'Content Cards',
				'cora_cat_ui': 'UI Elements'
			};
			const OTHER_CATEGORY = 'Other Components';

			// --- MAIN INIT ---
			function initCoraPanel() {
				const $searchArea = $('#elementor-panel-elements-search-area');
				const $elementsPage = $('#elementor-panel-page-elements');

				if ($searchArea.length === 0 || $('#cora-tab-switch').length > 0) return;

				// 1. Create Tabs
				const $toggleHTML = $(`
					<div id="cora-tab-switch">
						<div class="cora-tab active" data-tab="elementor">Elementor</div>
						<div class="cora-tab" data-tab="cora">
							<i class="eicon-apps"></i> Cora Studio
						</div>
					</div>
				`);

				// 2. Create Cora View
				const $coraView = $(`
					<div id="cora-custom-view" style="display:none;">
						<div class="cora-search-wrap">
							<i class="eicon-search"></i>
							<input type="text" id="cora-search-input" placeholder="Find a component...">
						</div>
						<div id="cora-widgets-container"></div>
					</div>
				`);

				$searchArea.before($toggleHTML);
				$elementsPage.append($coraView);

				// 3. Populate Widgets
				populateGroupedWidgets($('#cora-widgets-container'));

				// 4. Tab Switching
				$('#cora-tab-switch .cora-tab').on('click', function() {
					const tab = $(this).data('tab');
					$('#cora-tab-switch .cora-tab').removeClass('active');
					$(this).addClass('active');

					if (tab === 'cora') {
						$('#elementor-panel-elements-categories').hide();
						$('#elementor-panel-elements-search-area').hide();
						$('#cora-custom-view').fadeIn(200);
						
						// IMPORTANT: Refresh Drag logic when tab opens
						setTimeout(enableNativeDrag, 200);
					} else {
						$('#elementor-panel-elements-categories').show();
						$('#elementor-panel-elements-search-area').show();
						$('#cora-custom-view').hide();
					}
				});

				// 5. Search Logic
				$('#cora-search-input').on('keyup', function() {
					const val = $(this).val().toLowerCase();
					$('.elementor-element-wrapper').each(function() {
						const text = $(this).find('.title').text().toLowerCase();
						$(this).toggle(text.indexOf(val) > -1);
					});
					$('.cora-cat-group').each(function() {
						$(this).toggle($(this).find('.elementor-element-wrapper:visible').length > 0);
					});
				});

				console.log("Cora Studio: Ready");
			}

			// --- HELPER: Enable Native Drag & Drop ---
			function enableNativeDrag() {
				// 1. Get Elementor's official drag settings
				if (!elementor.getPanelView() || !elementor.getPanelView().getCurrentPageView()) return;
				
				const nativeOptions = elementor.getPanelView().getCurrentPageView().getDraggableOptions();
				
				// 2. Apply to our cards
				$('#cora-custom-view .elementor-element-wrapper').each(function() {
					if ($(this).data('ui-draggable')) {
						$(this).draggable('destroy'); // Reset if exists
					}

					$(this).draggable({
						...nativeOptions,
						// We override helper to ensure it looks right and sits on top
						helper: function(event) {
							const $clone = $(this).clone();
							$clone.addClass('elementor-draggable-helper elementor-element-wrapper');
							$clone.css({
								'width': '140px',
								'height': 'auto',
								'background': '#fff',
								'box-shadow': '0 10px 20px rgba(0,0,0,0.15)',
								'z-index': 99999,
								'opacity': 0.9
							});
							return $clone;
						},
						// Ensure it connects to the iframe
						connectToSortable: elementor.getPanelView().getCurrentPageView().getDraggableOptions().connectToSortable,
						scope: 'elementor-element' // CRITICAL: This connects it to the preview area
					});
				});
			}

			// --- HELPER: Build Grid ---
			function populateGroupedWidgets($container) {
				const allWidgets = elementor.config.widgets;
				let groups = { 'cora_cat_hero': [], 'cora_cat_cards': [], 'cora_cat_ui': [], 'other': [] };

				$.each(allWidgets, function(key, widget) {
					// Is it a Cora widget?
					const isCora = (key.indexOf('cora') === 0) || (widget.categories && widget.categories.includes('cora_widgets'));
					
					if (isCora) {
						// AUTO-SORT LOGIC (Fixes items stuck in "Other")
						let targetCat = 'other';
						const titleLower = widget.title.toLowerCase();

						// 1. Check strict category
						if (widget.categories) {
							if (widget.categories.includes('cora_cat_hero')) targetCat = 'cora_cat_hero';
							else if (widget.categories.includes('cora_cat_cards')) targetCat = 'cora_cat_cards';
							else if (widget.categories.includes('cora_cat_ui')) targetCat = 'cora_cat_ui';
						}

						// 2. If still "other", try to guess based on name
						if (targetCat === 'other') {
							if (titleLower.includes('hero') || titleLower.includes('heading') || titleLower.includes('newsletter')) {
								targetCat = 'cora_cat_hero';
							} else if (titleLower.includes('card') || titleLower.includes('grid') || titleLower.includes('pricing')) {
								targetCat = 'cora_cat_cards';
							} else if (titleLower.includes('badge') || titleLower.includes('button') || titleLower.includes('pill')) {
								targetCat = 'cora_cat_ui';
							}
						}

						groups[targetCat].push({ key: key, ...widget });
					}
				});

				let fullHTML = '';
				const renderGroup = (id, title, items) => {
					if (items.length === 0) return '';
					
					let grid = `<div class="cora-cat-group"><h4 class="cora-cat-title">${title} <span class="cora-count">${items.length}</span></h4><div class="cora-grid">`;
					
					items.forEach(w => {
						const cleanTitle = w.title.replace('Cora ', '');
						
						// Using Elementor's native class names to help with styling/behavior match
						grid += `
							<div class="elementor-element-wrapper" data-element-type="widget" data-widget-type="${w.key}">
								<div class="elementor-element--icon">
									<i class="${w.icon}"></i>
								</div>
								<div class="elementor-element--title title">${cleanTitle}</div>
							</div>
						`;
					});
					grid += `</div></div>`;
					return grid;
				};

				fullHTML += renderGroup('cora_cat_hero', CATEGORY_MAP['cora_cat_hero'], groups['cora_cat_hero']);
				fullHTML += renderGroup('cora_cat_cards', CATEGORY_MAP['cora_cat_cards'], groups['cora_cat_cards']);
				fullHTML += renderGroup('cora_cat_ui', CATEGORY_MAP['cora_cat_ui'], groups['cora_cat_ui']);
				fullHTML += renderGroup('other', OTHER_CATEGORY, groups['other']);

				$container.html(fullHTML);
			}

			// --- INIT LOOP ---
			let attempts = 0;
			const initInterval = setInterval(() => {
				initCoraPanel();
				attempts++;
				if ($('#cora-tab-switch').length > 0 || attempts > 20) clearInterval(initInterval);
			}, 500);

		});
		</script>

		<style>
			/* --- TABS --- */
			#cora-tab-switch { display: flex; background: #fff; border-bottom: 1px solid #E5E7EB; padding: 0 10px; }
			.cora-tab { flex: 1; text-align: center; padding: 14px 0; font-size: 13px; font-weight: 600; color: #6B7280; cursor: pointer; border-bottom: 2px solid transparent; display: flex; align-items: center; justify-content: center; gap: 8px; }
			.cora-tab:hover { color: #111827; background: #F9FAFB; }
			.cora-tab.active { color: #4F46E5; border-bottom-color: #4F46E5; background: transparent; }

			/* --- CONTAINER --- */
			#cora-custom-view { background: #F9FAFB; height: 100%; overflow-y: auto; padding: 20px; box-sizing: border-box; padding-bottom: 100px; }
			
			/* --- SEARCH --- */
			.cora-search-wrap { position: relative; margin-bottom: 24px; }
			.cora-search-wrap i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 14px; }
			#cora-search-input { width: 100%; padding: 10px 10px 10px 36px; border: 1px solid #E5E7EB; border-radius: 8px; font-size: 13px; outline: none; background: #fff; }
			#cora-search-input:focus { border-color: #4F46E5; box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1); }

			/* --- CATEGORIES --- */
			.cora-cat-group { margin-bottom: 32px; }
			.cora-cat-title { font-size: 12px; text-transform: uppercase; color: #6B7280; margin: 0 0 12px 0; letter-spacing: 0.5px; display: flex; align-items: center; justify-content: space-between; }
			.cora-count { background: #E5E7EB; color: #4B5563; padding: 2px 6px; border-radius: 4px; font-size: 10px; }

			/* --- GRID --- */
			.cora-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
			
			/* --- WIDGET CARD --- */
			#cora-custom-view .elementor-element-wrapper {
				background: #fff;
				border: 1px solid #E5E7EB;
				border-radius: 10px;
				padding: 16px 12px;
				display: flex;
				flex-direction: column;
				align-items: center;
				justify-content: center;
				text-align: center;
				cursor: grab;
				transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
				height: 90px;
				box-sizing: border-box;
				position: relative;
				margin: 0;
			}

			#cora-custom-view .elementor-element-wrapper:hover {
				border-color: #4F46E5;
				transform: translateY(-2px);
				box-shadow: 0 4px 12px rgba(0,0,0,0.08);
			}

			.elementor-element--icon { margin-bottom: 8px; color: #374151; transition: color 0.2s; font-size: 24px; }
			#cora-custom-view .elementor-element-wrapper:hover .elementor-element--icon { color: #4F46E5; }
			.elementor-element--title { font-size: 11px; font-weight: 500; color: #4B5563; line-height: 1.2; }
			
			/* FIX Z-INDEX for Dragging */
			.ui-draggable-dragging { z-index: 99999 !important; }
		</style>
		<?php
	}
}