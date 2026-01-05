<?php

//TITLE
$website_id = website_setting(0);
$expanded_space = in_array($website_id , $this->config->item('userids___31025'));
$user_session = user_session();

if(in_array($website_id, $this->config->item('userids___30984'))){
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'homecss_black_font\'); }); </script> ';
} else {
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'homecss_white_font\'); }); </script> ';
}

// Set page title
echo ' <script> $(document).ready(function () { $(document).prop(\'title\', \''.get_domain('m__name').' | AI Prompt Interface\'); }); </script> ';

$users___12273 = $this->config->item('users___12273'); //POST Cache
$users___11035 = $this->config->item('users___11035'); //Encyclopedia

// Top 8 AI Models with Recent Versions
$ai_models = array(
    'openai' => array(
        'name' => 'OpenAI',
        'icon' => '<i class="fas fa-robot"></i>',
        'color' => '#10a37f',
        'versions' => array(
            'gpt-4o' => 'GPT-4o (Latest)',
            'gpt-4-turbo' => 'GPT-4 Turbo',
            'gpt-4' => 'GPT-4',
            'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
        )
    ),
    'anthropic' => array(
        'name' => 'Anthropic Claude',
        'icon' => '<i class="fas fa-brain"></i>',
        'color' => '#d97757',
        'versions' => array(
            'claude-3-5-sonnet-20241022' => 'Claude 3.5 Sonnet (Latest)',
            'claude-3-opus-20240229' => 'Claude 3 Opus',
            'claude-3-sonnet-20240229' => 'Claude 3 Sonnet',
            'claude-3-haiku-20240307' => 'Claude 3 Haiku',
        )
    ),
    'google' => array(
        'name' => 'Google Gemini',
        'icon' => '<i class="fas fa-gem"></i>',
        'color' => '#4285f4',
        'versions' => array(
            'gemini-1.5-pro' => 'Gemini 1.5 Pro (Latest)',
            'gemini-1.5-flash' => 'Gemini 1.5 Flash',
            'gemini-pro' => 'Gemini Pro',
            'gemini-1.0-pro' => 'Gemini 1.0 Pro',
        )
    ),
    'meta' => array(
        'name' => 'Meta Llama',
        'icon' => '<i class="fas fa-code"></i>',
        'color' => '#0867fb',
        'versions' => array(
            'llama-3-70b' => 'Llama 3 70B (Latest)',
            'llama-3-8b' => 'Llama 3 8B',
            'llama-2-70b' => 'Llama 2 70B',
            'llama-2-13b' => 'Llama 2 13B',
        )
    ),
    'xai' => array(
        'name' => 'xAI Grok',
        'icon' => '<i class="fas fa-bolt"></i>',
        'color' => '#000000',
        'versions' => array(
            'grok-2' => 'Grok-2 (Latest)',
            'grok-beta' => 'Grok Beta',
            'grok-vision-beta' => 'Grok Vision Beta',
        )
    ),
    'mistral' => array(
        'name' => 'Mistral AI',
        'icon' => '<i class="fas fa-wind"></i>',
        'color' => '#ff6b35',
        'versions' => array(
            'mistral-large' => 'Mistral Large (Latest)',
            'mistral-medium' => 'Mistral Medium',
            'mistral-small' => 'Mistral Small',
            'mixtral-8x7b' => 'Mixtral 8x7B',
        )
    ),
    'cohere' => array(
        'name' => 'Cohere',
        'icon' => '<i class="fas fa-network-wired"></i>',
        'color' => '#ff6b6b',
        'versions' => array(
            'command-r-plus' => 'Command R+ (Latest)',
            'command-r' => 'Command R',
            'command' => 'Command',
            'command-light' => 'Command Light',
        )
    ),
    'perplexity' => array(
        'name' => 'Perplexity',
        'icon' => '<i class="fas fa-search"></i>',
        'color' => '#6366f1',
        'versions' => array(
            'pplx-70b-online' => '70B Online (Latest)',
            'pplx-7b-online' => '7B Online',
            'sonar' => 'Sonar',
        )
    ),
);

?>

<div class="ai-prompt-wrapper" style="min-height: calc(100vh - 200px); display: flex; flex-direction: column;">
    
    <!-- AI Model Selection - Compact Pills Style -->
    <div class="ai-models-bar" style="padding: 20px 0; border-bottom: 1px solid #e0e0e0; background: #fafafa;">
        <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
            <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                <span style="font-weight: 600; margin-right: 12px; color: #666;">Select Models:</span>
                <?php foreach($ai_models as $ai_key => $ai_model): ?>
                <div class="ai-model-pill" data-ai-key="<?= $ai_key ?>" style="display: inline-flex; align-items: center; padding: 6px 12px; border-radius: 20px; background: white; border: 2px solid #e0e0e0; cursor: pointer; transition: all 0.2s; user-select: none;">
                    <input type="checkbox" class="ai-model-checkbox" value="<?= $ai_key ?>" id="ai_<?= $ai_key ?>" 
                           data-ai-key="<?= $ai_key ?>" style="margin: 0 6px 0 0; cursor: pointer;">
                    <span style="color: <?= $ai_model['color'] ?>; margin-right: 6px;"><?= $ai_model['icon'] ?></span>
                    <span style="font-size: 0.9em; font-weight: 500;"><?= $ai_model['name'] ?></span>
                    <div class="ai-version-dropdown" id="version_<?= $ai_key ?>" style="display: none; margin-left: 8px;">
                        <select class="form-select form-select-sm ai-version-select" 
                                data-ai-key="<?= $ai_key ?>" multiple 
                                style="font-size: 0.75em; padding: 2px 8px; border-radius: 12px; min-width: 150px;">
                            <?php foreach($ai_model['versions'] as $version_key => $version_name): ?>
                            <option value="<?= $version_key ?>"><?= $version_name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <?php endforeach; ?>
                <button type="button" class="btn btn-sm" id="select-all-ais" style="margin-left: auto; padding: 6px 12px; border-radius: 20px;">
                    <i class="fas fa-check-double"></i> All
                </button>
            </div>
        </div>
    </div>

    <!-- Response Area - Scrollable -->
    <div class="ai-responses-container" id="response-container" style="flex: 1; overflow-y: auto; padding: 40px 20px; max-width: 1400px; margin: 0 auto; width: 100%;">
        <div style="text-align: center; color: #999; padding: 60px 20px;">
            <i class="fas fa-sparkles" style="font-size: 3em; margin-bottom: 20px; opacity: 0.3;"></i>
            <p style="font-size: 1.1em;">Start a conversation with AI</p>
            <p style="font-size: 0.9em; margin-top: 8px;">Select models above and type your prompt below</p>
        </div>
    </div>

    <!-- Fixed Input Area - Gemini/Grok Style -->
    <div class="ai-input-container" style="position: sticky; bottom: 0; background: white; border-top: 1px solid #e0e0e0; padding: 16px 0; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
        <div class="container" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
            
            <!-- Main Prompt Input - Using modal31911 structure -->
            <div class="ai-prompt-box" style="position: relative; background: #f8f9fa; border-radius: 24px; border: 1px solid #e0e0e0; transition: all 0.2s; padding: 12px 16px;">
                
                <!-- Post Message - Main Input -->
                <div class="dynamic_editing_input" style="margin: 0 !important;">
                    <textarea
                        class="form-control note-textarea algolia_finder new-note editing-mode unsaved_warning algolia__e algolia__i save_postmessageraw ai-prompt-textarea"
                        id="ai-prompt-text"
                        placeholder="Ask anything or attach files..."
                        style="margin:0; width:100%; background-color: transparent !important; border: none !important; resize: none; min-height: 24px; max-height: 200px; font-size: 15px; line-height: 1.5; padding: 0; box-shadow: none !important;"
                        rows="1"></textarea>
                </div>

                <!-- Image Preview -->
                <div id="image-preview-inline" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; margin-bottom: 8px;"></div>

                <!-- Action Buttons Row -->
                <div class="inner_message left_padded" style="display: flex; align-items: center; justify-content: space-between; margin-top: 8px; padding-top: 8px; border-top: 1px solid #e8e8e8;">
                    <div style="display: flex; align-items: center; gap: 4px;">
                        <?php
                        // File Upload
                        echo '<div class="dynamic_editing_input no_padded">';
                        echo '<a class="uploader_13572 icon-block ai-action-btn" href="javascript:void(0)" title="Upload File" style="padding: 8px; border-radius: 50%; transition: background 0.2s;"><i class="fas fa-paperclip"></i></a>';
                        echo '</div>';

                        // Emoji
                        echo '<div class="dynamic_editing_input no_padded" style="margin: 0 !important;">';
                        echo '<div class="dropdown emoji_selector">';
                        echo '<button type="button" class="btn no-left-padding no-right-padding icon-block ai-action-btn" id="emoji_ai" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Add Emoji" style="padding: 8px; border-radius: 50%; transition: background 0.2s; border: none; background: transparent;"><i class="far fa-face-smile"></i></button>';
                        echo '<div class="dropdown-menu emoji_i" aria-labelledby="emoji_ai"></div>';
                        echo '</div>';
                        echo '</div>';

                        // AI Model Selector Dropdown
                        echo '<div class="dynamic_editing_input no_padded compact_dropdown">';
                        echo '<button class="btn btn-secondary dropdown-toggle icon-block ai-action-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="Select AI Models" id="ai_model_selector" style="padding: 8px 12px; border-radius: 20px; font-size: 0.85em; border: 1px solid #e0e0e0; background: white;">';
                        echo '<i class="fas fa-sliders-h"></i> <span id="selected-models-count">0</span> selected';
                        echo '</button>';
                        echo '<ul class="dropdown-menu left-padded-menu" aria-labelledby="ai_model_selector" style="max-height: 400px; overflow-y: auto;">';
                        foreach($ai_models as $ai_key => $ai_model) {
                            echo '<li class="grey"><span class="dropdown-item" style="font-weight: 600; color: ' . $ai_model['color'] . ';"><span class="icon-block-sm">' . $ai_model['icon'] . '</span>' . $ai_model['name'] . ':</span></li>';
                            foreach($ai_model['versions'] as $version_key => $version_name) {
                                echo '<li class="inline-block"><label class="dropdown-item inline-block" style="cursor: pointer; margin: 0; padding: 6px 20px;">';
                                echo '<input type="checkbox" class="ai-version-checkbox-inline" data-ai-key="' . $ai_key . '" value="' . $version_key . '" style="margin-right: 8px;">';
                                echo $version_name;
                                echo '</label></li>';
                            }
                        }
                        echo '</ul>';
                        echo '</div>';

                        // Advanced Options
                        echo '<div class="dynamic_editing_input no_padded">';
                        echo '<button class="btn btn-secondary icon-block ai-action-btn" type="button" data-bs-toggle="collapse" data-bs-target="#advancedOptions" aria-expanded="false" title="Advanced Options" style="padding: 8px; border-radius: 50%; border: none; background: transparent;"><i class="fas fa-cog"></i></button>';
                        echo '</div>';
                        ?>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <button type="button" class="btn btn-primary ai-send-btn" id="submit-prompt" 
                                style="border-radius: 20px; padding: 10px 24px; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                            <i class="fas fa-paper-plane"></i> Send
                        </button>
                    </div>
                </div>

                <!-- Advanced Options Collapse -->
                <div class="collapse" id="advancedOptions" style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #e8e8e8;">
                    <div class="row" style="margin: 0;">
                        <div class="col-md-4 mb-2">
                            <label style="font-size: 0.85em; color: #666; margin-bottom: 4px;">Temperature: <span id="temp-value">0.7</span></label>
                            <input type="range" class="form-range" id="temperature" min="0" max="2" step="0.1" value="0.7" style="height: 4px;">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label style="font-size: 0.85em; color: #666; margin-bottom: 4px;">Max Tokens</label>
                            <input type="number" class="form-control form-control-sm" id="max-tokens" value="2000" min="1" max="8000">
                        </div>
                        <div class="col-md-4 mb-2">
                            <label style="font-size: 0.85em; color: #666; margin-bottom: 4px;">Response Format</label>
                            <select class="form-select form-select-sm" id="response-format">
                                <option value="text">Plain Text</option>
                                <option value="markdown">Markdown</option>
                                <option value="json">JSON</option>
                                <option value="html">HTML</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-2">
                        <label style="font-size: 0.85em; color: #666; margin-bottom: 4px;">System Prompt (Optional)</label>
                        <textarea class="form-control form-control-sm" id="system-prompt" rows="2" 
                                  placeholder="Define the AI's role or behavior..." style="font-size: 0.9em;"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ai-prompt-wrapper {
    background: #ffffff;
}

.ai-model-pill {
    transition: all 0.2s ease;
}

.ai-model-pill:hover {
    border-color: #007bff !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.ai-model-checkbox:checked ~ span,
.ai-model-checkbox:checked + span {
    opacity: 1;
}

.ai-model-pill:has(.ai-model-checkbox:checked) {
    border-color: #007bff !important;
    background: #e7f3ff !important;
}

.ai-action-btn {
    color: #666;
    transition: all 0.2s;
}

.ai-action-btn:hover {
    background: #f0f0f0 !important;
    color: #333;
}

.ai-prompt-box:focus-within {
    border-color: #007bff !important;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1) !important;
}

.ai-prompt-textarea:focus {
    outline: none !important;
    box-shadow: none !important;
}

.ai-send-btn {
    transition: all 0.2s;
}

.ai-send-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15) !important;
}

.ai-send-btn:active {
    transform: translateY(0);
}

.image-preview-inline-item {
    position: relative;
    display: inline-block;
    margin-right: 8px;
    margin-bottom: 8px;
}

.image-preview-inline-item img {
    max-width: 100px;
    max-height: 100px;
    border-radius: 8px;
    border: 1px solid #e0e0e0;
}

.image-preview-inline-item .remove-image {
    position: absolute;
    top: -6px;
    right: -6px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    cursor: pointer;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.ai-response-item {
    margin-bottom: 24px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    border-left: 4px solid #007bff;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.ai-response-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
    padding-bottom: 12px;
    border-bottom: 1px solid #e0e0e0;
}

.ai-response-title {
    font-weight: 600;
    color: #007bff;
    display: flex;
    align-items: center;
    gap: 8px;
}

.ai-response-time {
    font-size: 0.85em;
    color: #999;
}

.ai-response-content {
    white-space: pre-wrap;
    word-wrap: break-word;
    line-height: 1.6;
    color: #333;
}

.loading-spinner {
    text-align: center;
    padding: 20px;
    color: #999;
}

@media (max-width: 768px) {
    .ai-models-bar {
        padding: 12px 0 !important;
    }
    
    .ai-model-pill {
        font-size: 0.85em;
        padding: 4px 8px !important;
    }
    
    .ai-prompt-box {
        border-radius: 16px !important;
        padding: 10px 12px !important;
    }
    
    .ai-input-container {
        padding: 12px 0 !important;
    }
}
</style>

<script>
$(document).ready(function() {
    
    let uploadedImages = [];
    
    // Auto-resize textarea
    $('#ai-prompt-text').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Show/hide version selector when AI is selected
    $('.ai-model-checkbox').on('change', function() {
        const aiKey = $(this).data('ai-key');
        const versionSelector = $('#version_' + aiKey);
        if ($(this).is(':checked')) {
            versionSelector.slideDown(200);
        } else {
            versionSelector.slideUp(200);
        }
        updateSelectedCount();
    });

    // Update selected models count
    function updateSelectedCount() {
        const count = $('.ai-model-checkbox:checked').length;
        $('#selected-models-count').text(count);
        if (count > 0) {
            $('#ai_model_selector').addClass('btn-primary').removeClass('btn-secondary');
        } else {
            $('#ai_model_selector').addClass('btn-secondary').removeClass('btn-primary');
        }
    }

    // Select All AIs
    $('#select-all-ais').on('click', function() {
        $('.ai-model-checkbox').prop('checked', true).trigger('change');
    });

    // Image upload handler (using existing uploader_13572 functionality)
    // The uploader_13572 should be handled by existing website.js code
    
    // Image preview for inline display
    $(document).on('change', 'input[type="file"]', function(e) {
        if ($(this).attr('accept') && $(this).attr('accept').includes('image')) {
            const files = Array.from(e.target.files);
            files.forEach(file => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        uploadedImages.push({
                            file: file,
                            dataUrl: e.target.result
                        });
                        updateImagePreview();
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });

    function updateImagePreview() {
        const preview = $('#image-preview-inline');
        preview.empty();
        if (uploadedImages.length > 0) {
            preview.show();
            uploadedImages.forEach((img, index) => {
                const item = $('<div class="image-preview-inline-item"></div>');
                item.append(`<img src="${img.dataUrl}" alt="Preview ${index + 1}">`);
                item.append(`<button type="button" class="remove-image" data-index="${index}"><i class="fas fa-times"></i></button>`);
                preview.append(item);
            });
        } else {
            preview.hide();
        }
    }

    $(document).on('click', '.remove-image', function() {
        const index = $(this).data('index');
        uploadedImages.splice(index, 1);
        updateImagePreview();
    });

    // Temperature slider
    $('#temperature').on('input', function() {
        $('#temp-value').text($(this).val());
    });

    // Submit prompt
    $('#submit-prompt').on('click', function() {
        const selectedAIs = [];
        $('.ai-model-checkbox:checked').each(function() {
            const aiKey = $(this).data('ai-key');
            const versions = [];
            
            // Check inline checkboxes first
            $(`.ai-version-checkbox-inline[data-ai-key="${aiKey}"]:checked`).each(function() {
                versions.push($(this).val());
            });
            
            // If no inline versions selected, check dropdown
            if (versions.length === 0) {
                $(`#version_${aiKey} .ai-version-select option:selected`).each(function() {
                    versions.push($(this).val());
                });
            }
            
            // If still no versions, use all versions
            if (versions.length === 0) {
                $(`#version_${aiKey} .ai-version-select option`).each(function() {
                    versions.push($(this).val());
                });
            }
            
            selectedAIs.push({
                provider: aiKey,
                versions: versions
            });
        });

        if (selectedAIs.length === 0) {
            alert('Please select at least one AI model.');
            return;
        }

        const promptText = $('#ai-prompt-text').val().trim();
        if (!promptText && uploadedImages.length === 0) {
            alert('Please enter a prompt or upload an image.');
            return;
        }

        // Clear empty state
        $('.ai-responses-container').html('');

        // Add user message
        if (promptText) {
            const userMsg = $(`
                <div class="ai-response-item" style="background: #e3f2fd; border-left-color: #2196f3;">
                    <div class="ai-response-content">${promptText.replace(/\n/g, '<br>')}</div>
                </div>
            `);
            $('.ai-responses-container').append(userMsg);
        }

        // Process each selected AI
        selectedAIs.forEach(function(aiConfig) {
            aiConfig.versions.forEach(function(version) {
                const responseId = `response_${aiConfig.provider}_${version}_${Date.now()}`;
                const aiModel = <?= json_encode($ai_models) ?>[aiConfig.provider];
                
                const responseDiv = $(`
                    <div class="ai-response-item" id="${responseId}">
                        <div class="ai-response-header">
                            <div class="ai-response-title">
                                <span style="color: ${aiModel.color};">${aiModel.icon}</span>
                                ${aiModel.name} - ${version}
                            </div>
                            <div class="ai-response-time">
                                <i class="fas fa-clock"></i> Processing...
                            </div>
                        </div>
                        <div class="ai-response-content">
                            <div class="loading-spinner">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2" style="font-size: 0.9em;">Sending request...</p>
                            </div>
                        </div>
                    </div>
                `);
                $('.ai-responses-container').append(responseDiv);

                // Simulate API call (replace with actual API integration)
                setTimeout(function() {
                    const content = `This is a simulated response from ${aiModel.name} (${version}).\n\n` +
                                  `Your prompt: "${promptText.substring(0, 100)}${promptText.length > 100 ? '...' : ''}"\n\n` +
                                  `In a real implementation, this would make an API call to ${aiModel.name} with the selected model version. ` +
                                  `The response would include the actual AI-generated content based on your prompt.`;
                    
                    $(`#${responseId} .ai-response-content`).html(`<pre style="white-space: pre-wrap; font-family: inherit; margin: 0;">${content}</pre>`);
                    $(`#${responseId} .ai-response-time`).html(`<i class="fas fa-check-circle text-success"></i> ${new Date().toLocaleTimeString()}`);
                }, 1000 + Math.random() * 2000);
            });
        });

        // Clear input
        $('#ai-prompt-text').val('').css('height', 'auto');
        uploadedImages = [];
        updateImagePreview();

        // Scroll to bottom
        $('.ai-responses-container').animate({
            scrollTop: $('.ai-responses-container')[0].scrollHeight
        }, 300);
    });

    // Enter key to submit (Shift+Enter for new line)
    $('#ai-prompt-text').on('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            $('#submit-prompt').click();
        }
    });

    // Sync inline checkboxes with main checkboxes
    $('.ai-version-checkbox-inline').on('change', function() {
        const aiKey = $(this).data('ai-key');
        const version = $(this).val();
        const isChecked = $(this).is(':checked');
        
        // Update dropdown if exists
        $(`#version_${aiKey} .ai-version-select option[value="${version}"]`).prop('selected', isChecked);
    });

    updateSelectedCount();
});
</script>

<?php

//SOCIAL FOOTER (similar to home.php)
$domain_phone =  website_setting(28615);
$email_domain =  website_setting(28614);

//Footer links
$social_ui = null;
$users___14870 = $this->config->item('users___14870');
foreach($this->config->item('users___14036') as $userid => $m){
    foreach($this->Chains->read(array(
        'chainuserinput' => $userid,
        'chainuseroutput' => $website_id,
        'chainusertype IN (' . join(',', $this->config->item('userids___13548')) . ')' => null,
        ), array(), 0, 0) as $social_chain){

        if(filter_var($social_chain['chainvalue'], FILTER_VALIDATE_URL) && view_url_clean($social_chain['chainvalue'])!=view_url_clean($users___14870[$website_id]['m__message'])){
            $social_url = $social_chain['chainvalue'];
        } elseif(filter_var($social_chain['chainvalue'], FILTER_VALIDATE_EMAIL)){
            $social_url = 'mailto:'.$social_chain['chainvalue'];
        } elseif(strlen(preg_replace("/[^0-9]/", "", $social_chain['chainvalue'])) > 5){
            $social_url = phone_href($userid, $social_chain['chainvalue']);
        } else {
            continue;
        }

        $social_ui .= '<li><a href="'.$social_url.'" data-toggle="tooltip" data-placement="top" title="'.$m['m__name'].'">'.$m['m__cover'].'</a></li>';
    }
}

if($social_ui){
    echo '<div class="narrow-bar slim_flat">';
    echo '<div class="social-footer">';
    echo '<ul class="social-ul">';
    echo $social_ui;
    echo '</ul>';
    echo '</div>';
    echo '</div>';
}

echo '<div class="bottom_spacer">&nbsp;</div>';

?>
