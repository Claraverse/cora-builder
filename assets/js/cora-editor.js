jQuery(window).on('elementor:init', function() {
    
    // We use an interval to check for the header, ensuring it works 
    // even if Elementor takes 5 seconds to load.
    var coraButtonInterval = setInterval(function() {
        
        var $headerControls = jQuery('#elementor-panel-header-controls');
        
        // If header exists AND button doesn't exist yet
        if ($headerControls.length > 0 && jQuery('#cora-builder-toggle').length === 0) {
            
            // Create the Button
            var $coraBtn = jQuery('<div id="cora-builder-toggle" class="elementor-panel-header-menu-item" title="Toggle Cora Builder Mode"><i class="eicon-apps"></i></div>');
            
            // Insert it
            $headerControls.prepend($coraBtn);
            
            // Add Click Event
            $coraBtn.on('click', function() {
                jQuery('body').toggleClass('cora-builder-active');
                
                // Trigger resize to fix any grid layout glitches
                window.dispatchEvent(new Event('resize'));
            });

            // Stop checking
            clearInterval(coraButtonInterval);
            console.log('Cora Builder Button Injected');
        }
    }, 500); // Check every 0.5 seconds
});