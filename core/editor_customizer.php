<?php
namespace Cora_Builder\Core;

if (!defined('ABSPATH'))
	exit;

class Editor_Customizer
{

	public function __construct()
	{
		add_action('elementor/editor/footer', [$this, 'render_cora_panel']);
	}

	public function render_cora_panel()
	{
		?>
		<script>
			jQuery(document).ready(function ($) {

				const CATEGORY_MAP = {
					'cora_cat_hero': 'Heroes & Headers',
					'cora_cat_cards': 'Content Cards',
					'cora_cat_ui': 'UI Elements'
				};

				function initCoraPanel() {
					if ($('#cora-studio-nav').length > 0) return;

					const $panelHeader = $('#elementor-panel-header');
					const $elementsPage = $('#elementor-panel-page-elements');

					// 1. Inject Cora Studio Header (Hidden by default)
					const $studioHeader = $(`
					<div id="cora-studio-header" style="display:none;">
						<div class="cora-logo-area">✨ Cora Studio</div>
						<div id="cora-studio-nav">
							<div class="cora-nav-item active" data-tab="elements">Elements</div>
							<div class="cora-nav-item">Components</div>
							<div class="cora-nav-item">Widgets</div>
							<div class="cora-nav-item">Section</div>
						</div>
					</div>
				`);

					// 2. Inject Search & View
					const $coraView = $(`
					<div id="cora-studio-view" style="display:none;">
						<div class="cora-search-container">
							<i class="eicon-search"></i>
							<input type="text" id="cora-internal-search" placeholder="Find a component...">
						</div>
						<div id="cora-widgets-list"></div>
					</div>
				`);

					// 3. Inject Bottom Switcher
					const $switcher = $(`
					<div class="cora-mode-switcher-wrap df">
						<div class="cora-mode-switcher">
							<button class="mode-btn active" data-mode="default">Default mode</button>
							<button class="mode-btn" data-mode="cora">Cora Mode</button>
						</div>
					</div>
				`);

					$panelHeader.prepend($studioHeader);
					$elementsPage.append($coraView);
					$('body').append($switcher);

					populateWidgets();

					// 4. Mode Toggle Logic
					$('.mode-btn').on('click', function () {
						const mode = $(this).data('mode');
						$('.mode-btn').removeClass('active');
						$(this).addClass('active');

						if (mode === 'cora') {
							$('#elementor-panel-elements-navigation, #elementor-panel-elements-search-area, #elementor-panel-elements-categories').hide();
							$('#cora-studio-header, #cora-studio-view').show();
							setTimeout(enableDraggable, 300); // Re-init drag for new view
						} else {
							$('#cora-studio-header, #cora-studio-view').hide();
							$('#elementor-panel-elements-navigation, #elementor-panel-elements-search-area, #elementor-panel-elements-categories').show();
						}
					});
				}

				function enableDraggable() {
					// Connect to Elementor's native draggable engine
					const draggableOptions = elementor.getPanelView().getCurrentPageView().getDraggableOptions();

					$('#cora-studio-view .elementor-element').draggable({
						...draggableOptions,
						helper: function () {
							const $clone = $(this).clone();
							$clone.css({ 'width': '120px', 'height': '100px', 'background': '#fff', 'z-index': 10000 });
							return $clone;
						},
						appendTo: 'body',
						cursorAt: { left: 60, top: 50 }
					});
				}

				function populateWidgets() {
					const allWidgets = elementor.config.widgets;
					let html = '';

					// Build category groups (Simplified for example)
					const categories = ['cora_cat_hero', 'cora_cat_cards'];

					categories.forEach(cat => {
						let items = Object.values(allWidgets).filter(w => w.categories && w.categories.includes('cora_widgets'));
						if (items.length === 0) return;

						html += `<div class="cora-cat-section">
						<h4 class="cora-cat-title">${CATEGORY_MAP[cat] || 'Components'} <span class="cora-count">${items.length}</span></h4>
						<div class="cora-card-grid">`;

						items.forEach(w => {
							html += `
							<div class="elementor-element-wrapper elementor-draggable" data-element-type="widget" data-widget-type="${w.widget_type || w.key}">
								<div class="elementor-element">
									<div class="icon"><i class="${w.icon}"></i></div>
									<div class="title">${w.title.replace('Cora ', '')}</div>
								</div>
							</div>`;
						});
						html += `</div></div>`;
					});
					$('#cora-widgets-list').html(html);
				}

				// Polling for Elementor Init
				const timer = setInterval(() => {
					if ($('#elementor-panel-header').length) {
						initCoraPanel();
						clearInterval(timer);
					}
				}, 500);
			});
		</script>

		<style>
			/* --- CORA STUDIO UI --- */
			#cora-studio-header {
				background: #fff;
				padding: 15px;
				flex-direction: column;
				border-bottom: 1px solid #eee;
			}

			.cora-logo-area {
				text-align: center;
				font-weight: 800;
				font-size: 16px;
				margin-bottom: 12px;
			}

			#cora-studio-nav {
				display: flex;
				background: #f0f0f0;
				border-radius: 8px;
				padding: 4px;
				gap: 4px;
			}

			.cora-nav-item {
				flex: 1;
				text-align: center;
				font-size: 11px;
				font-weight: 600;
				padding: 8px 2px;
				cursor: pointer;
				border-radius: 6px;
				color: #666;
			}

			.cora-nav-item.active {
				background: #fff;
				color: #000;
				box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
			}

			#cora-studio-view {
				padding: 15px;
				background: #f9f9f9;
				height: 100%;
				overflow-y: auto;
			}

			.cora-search-container {
				position: relative;
				margin-bottom: 20px;
			}

			.cora-search-container i {
				position: absolute;
				left: 10px;
				top: 50%;
				transform: translateY(-50%);
				color: #aaa;
			}

			#cora-internal-search {
				width: 100%;
				padding: 10px 10px 10px 30px;
				border: 1px solid #ddd;
				border-radius: 8px;
				outline: none;
			}

			.cora-cat-title {
				font-size: 11px;
				text-transform: uppercase;
				color: #888;
				margin-bottom: 10px;
				display: flex;
				justify-content: space-between;
			}

			.cora-card-grid {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 10px;
				margin-bottom: 25px;
			}

			#cora-studio-view .elementor-element {
				background: #fff;
				border: 1px solid #eee;
				border-radius: 12px;
				padding: 15px 5px;
				text-align: center;
				cursor: grab;
				transition: 0.2s;
			}

			#cora-studio-view .elementor-element:hover {
				border-color: #6366f1;
				transform: translateY(-2px);
			}

			#cora-studio-view .icon {
				font-size: 24px;
				margin-bottom: 8px;
				color: #444;
			}

			#cora-studio-view .title {
				font-size: 10px;
				font-weight: 600;
				color: #555;
			}

			/* Switcher */
			.cora-mode-switcher-wrap {
				position: fixed;
				bottom: 20px;
				left: 40px;
				z-index: 9999;
			}

			.cora-mode-switcher {
				background: #fff;
				padding: 5px;
				border-radius: 50px;
				display: flex;
				box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
				border: 1px solid #eee;
			}

			.mode-btn {
				border: none;
				background: none;
				padding: 8px 20px;
				border-radius: 40px;
				font-size: 12px;
				font-weight: 600;
				cursor: pointer;
				color: #777;
			}

			.mode-btn.active {
				background: #e0e0e0;
				color: #000;
			}
		</style>
		<?php
	}
}