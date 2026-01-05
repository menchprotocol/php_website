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
echo ' <script> $(document).ready(function () { $(document).prop(\'title\', \''.get_domain('m__name').' | AI Chat\'); $(\'body\').addClass(\'poe-page\'); }); </script> ';

$users___12273 = $this->config->item('users___12273'); //POST Cache
$users___11035 = $this->config->item('users___11035'); //Encyclopedia

// Top 8 AI Models with Recent Versions
$ai_models = array(
    'openai' => array(
        'name' => 'GPT-4o',
        'short_name' => 'GPT-4o',
        'icon' => '<i class="fas fa-robot"></i>',
        'color' => '#10a37f',
        'bg_color' => '#d1fae5',
        'versions' => array(
            'gpt-4o' => 'GPT-4o',
            'gpt-4-turbo' => 'GPT-4 Turbo',
            'gpt-4' => 'GPT-4',
            'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
        )
    ),
    'anthropic' => array(
        'name' => 'Claude',
        'short_name' => 'Claude',
        'icon' => '<i class="fas fa-brain"></i>',
        'color' => '#d97757',
        'bg_color' => '#fce7e0',
        'versions' => array(
            'claude-3-5-sonnet-20241022' => 'Claude 3.5 Sonnet',
            'claude-3-opus-20240229' => 'Claude 3 Opus',
            'claude-3-sonnet-20240229' => 'Claude 3 Sonnet',
            'claude-3-haiku-20240307' => 'Claude 3 Haiku',
        )
    ),
    'google' => array(
        'name' => 'Gemini',
        'short_name' => 'Gemini',
        'icon' => '<i class="fas fa-gem"></i>',
        'color' => '#4285f4',
        'bg_color' => '#e3f2fd',
        'versions' => array(
            'gemini-1.5-pro' => 'Gemini 1.5 Pro',
            'gemini-1.5-flash' => 'Gemini 1.5 Flash',
            'gemini-pro' => 'Gemini Pro',
            'gemini-1.0-pro' => 'Gemini 1.0 Pro',
        )
    ),
    'meta' => array(
        'name' => 'Llama',
        'short_name' => 'Llama',
        'icon' => '<i class="fas fa-code"></i>',
        'color' => '#0867fb',
        'bg_color' => '#dbeafe',
        'versions' => array(
            'llama-3-70b' => 'Llama 3 70B',
            'llama-3-8b' => 'Llama 3 8B',
            'llama-2-70b' => 'Llama 2 70B',
            'llama-2-13b' => 'Llama 2 13B',
        )
    ),
    'xai' => array(
        'name' => 'Grok',
        'short_name' => 'Grok',
        'icon' => '<i class="fas fa-bolt"></i>',
        'color' => '#000000',
        'bg_color' => '#f3f4f6',
        'versions' => array(
            'grok-2' => 'Grok-2',
            'grok-beta' => 'Grok Beta',
            'grok-vision-beta' => 'Grok Vision Beta',
        )
    ),
    'mistral' => array(
        'name' => 'Mistral',
        'short_name' => 'Mistral',
        'icon' => '<i class="fas fa-wind"></i>',
        'color' => '#ff6b35',
        'bg_color' => '#ffe5dc',
        'versions' => array(
            'mistral-large' => 'Mistral Large',
            'mistral-medium' => 'Mistral Medium',
            'mistral-small' => 'Mistral Small',
            'mixtral-8x7b' => 'Mixtral 8x7B',
        )
    ),
    'cohere' => array(
        'name' => 'Command R+',
        'short_name' => 'Cohere',
        'icon' => '<i class="fas fa-network-wired"></i>',
        'color' => '#ff6b6b',
        'bg_color' => '#ffe0e0',
        'versions' => array(
            'command-r-plus' => 'Command R+',
            'command-r' => 'Command R',
            'command' => 'Command',
            'command-light' => 'Command Light',
        )
    ),
    'perplexity' => array(
        'name' => 'Perplexity',
        'short_name' => 'Perplexity',
        'icon' => '<i class="fas fa-search"></i>',
        'color' => '#6366f1',
        'bg_color' => '#e0e7ff',
        'versions' => array(
            'pplx-70b-online' => '70B Online',
            'pplx-7b-online' => '7B Online',
            'sonar' => 'Sonar',
        )
    ),
);

?>

<div class="poe-container" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; display: flex; background: #ffffff; overflow: hidden; z-index: 1;">
    
    <!-- Left Sidebar - Model Selection (Poe.com style) -->
    <div class="poe-sidebar" style="width: 280px; background: #f9fafb; border-right: 1px solid #e5e7eb; display: flex; flex-direction: column; overflow-y: auto;">
        
        <!-- Sidebar Header -->
        <div style="padding: 20px; border-bottom: 1px solid #e5e7eb;">
            <h2 style="margin: 0; font-size: 1.25em; font-weight: 600; color: #111827;">AI Models</h2>
            <p style="margin: 8px 0 0 0; font-size: 0.875em; color: #6b7280;">Select models to chat with</p>
        </div>

        <!-- Model List -->
        <div style="flex: 1; padding: 12px; overflow-y: auto;">
            <?php foreach($ai_models as $ai_key => $ai_model): ?>
            <div class="poe-model-item" data-ai-key="<?= $ai_key ?>" 
                 style="padding: 12px; margin-bottom: 8px; border-radius: 12px; cursor: pointer; transition: all 0.2s; border: 2px solid transparent;"
                 onclick="selectModel('<?= $ai_key ?>')">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="poe-model-avatar" 
                         style="width: 40px; height: 40px; border-radius: 10px; background: <?= $ai_model['bg_color'] ?>; display: flex; align-items: center; justify-content: center; color: <?= $ai_model['color'] ?>; font-size: 1.2em; flex-shrink: 0;">
                        <?= $ai_model['icon'] ?>
                    </div>
                    <div style="flex: 1; min-width: 0;">
                        <div style="font-weight: 600; font-size: 0.9375em; color: #111827; margin-bottom: 2px;">
                            <?= $ai_model['name'] ?>
                        </div>
                        <div class="poe-model-version" style="font-size: 0.8125em; color: #6b7280;">
                            <select class="poe-version-select" data-ai-key="<?= $ai_key ?>" 
                                    style="border: none; background: transparent; color: #6b7280; font-size: inherit; cursor: pointer; width: 100%;"
                                    onchange="event.stopPropagation();">
                                <?php 
                                $first_version = true;
                                foreach($ai_model['versions'] as $version_key => $version_name): 
                                ?>
                                <option value="<?= $version_key ?>" <?= $first_version ? 'selected' : '' ?>><?= $version_name ?></option>
                                <?php 
                                $first_version = false;
                                endforeach; 
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="poe-model-checkbox-wrapper">
                        <input type="checkbox" class="poe-model-checkbox" 
                               data-ai-key="<?= $ai_key ?>" 
                               id="model_<?= $ai_key ?>"
                               style="width: 18px; height: 18px; cursor: pointer; accent-color: <?= $ai_model['color'] ?>;"
                               onchange="event.stopPropagation(); updateSelectedModels();">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Sidebar Footer -->
        <div style="padding: 16px; border-top: 1px solid #e5e7eb; background: white;">
            <button type="button" class="poe-btn-secondary" id="select-all-models" 
                    style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #e5e7eb; background: white; font-weight: 500; cursor: pointer; transition: all 0.2s;">
                Select All
            </button>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="poe-main" style="flex: 1; display: flex; flex-direction: column; background: #ffffff; overflow: hidden;">
        
        <!-- Chat Messages Area -->
        <div class="poe-messages" id="poe-messages-container" 
             style="flex: 1; overflow-y: auto; padding: 24px 24px 24px 24px; background: #ffffff; scroll-behavior: smooth;">
            
            <!-- Empty State -->
            <div class="poe-empty-state" id="poe-empty-state" 
                 style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; min-height: 400px; text-align: center; padding: 40px;">
                <div style="width: 80px; height: 80px; border-radius: 50%; background: #f3f4f6; display: flex; align-items: center; justify-content: center; margin-bottom: 24px;">
                    <i class="fas fa-comments" style="font-size: 2em; color: #9ca3af;"></i>
                </div>
                <h3 style="font-size: 1.5em; font-weight: 600; color: #111827; margin: 0 0 8px 0;">Start a conversation</h3>
                <p style="font-size: 0.9375em; color: #6b7280; margin: 0;">Select models from the sidebar and ask anything</p>
            </div>
        </div>

        <!-- Input Area - Fixed at Bottom -->
        <div class="poe-input-area" 
             style="border-top: 1px solid #e5e7eb; background: #ffffff; padding: 16px 24px; flex-shrink: 0;">
            
            <!-- Image Preview -->
            <div id="poe-image-preview" style="display: flex; gap: 8px; margin-bottom: 12px; flex-wrap: wrap;"></div>

            <!-- Input Box -->
            <div class="poe-input-box" 
                 style="position: relative; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 24px; padding: 12px 16px; display: flex; align-items: flex-end; gap: 8px; transition: all 0.2s; max-width: 100%;">
                
                <!-- Text Input - Using modal31911 structure -->
                <div class="dynamic_editing_input" style="flex: 1; margin: 0 !important;">
                    <textarea
                        class="form-control note-textarea algolia_finder new-note editing-mode unsaved_warning algolia__e algolia__i save_postmessageraw poe-textarea"
                        id="poe-prompt-text"
                        placeholder="Message..."
                        style="margin:0; width:100%; background-color: transparent !important; border: none !important; resize: none; min-height: 24px; max-height: 200px; font-size: 15px; line-height: 1.5; padding: 0; box-shadow: none !important; font-family: inherit;"
                        rows="1"></textarea>
                </div>

                <!-- Action Buttons -->
                <div class="inner_message left_padded" style="display: flex; align-items: center; gap: 4px; flex-shrink: 0;">
                    <?php
                    // File Upload
                    echo '<div class="dynamic_editing_input no_padded">';
                    echo '<a class="uploader_13572 icon-block poe-action-btn" href="javascript:void(0)" title="Attach File" style="padding: 8px; border-radius: 50%; transition: background 0.2s; color: #6b7280; text-decoration: none; display: flex; align-items: center; justify-content: center; width: 36px; height: 36px;"><i class="fas fa-paperclip"></i></a>';
                    echo '</div>';

                    // Emoji
                    echo '<div class="dynamic_editing_input no_padded" style="margin: 0 !important;">';
                    echo '<div class="dropdown emoji_selector">';
                    echo '<button type="button" class="btn no-left-padding no-right-padding icon-block poe-action-btn" id="emoji_poe" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Add Emoji" style="padding: 8px; border-radius: 50%; transition: background 0.2s; border: none; background: transparent; color: #6b7280; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;"><i class="far fa-face-smile"></i></button>';
                    echo '<div class="dropdown-menu emoji_i" aria-labelledby="emoji_poe"></div>';
                    echo '</div>';
                    echo '</div>';
                    ?>
                </div>

                <!-- Send Button -->
                <button type="button" class="poe-send-btn" id="poe-submit-prompt" 
                        style="width: 36px; height: 36px; border-radius: 50%; background: #111827; border: none; color: white; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; flex-shrink: 0;"
                        disabled>
                    <i class="fas fa-arrow-up" style="font-size: 0.875em;"></i>
                </button>
            </div>

            <!-- Advanced Options (Collapsed by default) -->
            <div class="poe-advanced-options" style="margin-top: 12px; display: none;" id="poe-advanced-options">
                <div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 150px;">
                        <label style="font-size: 0.8125em; color: #6b7280; margin-bottom: 4px; display: block;">Temperature: <span id="poe-temp-value">0.7</span></label>
                        <input type="range" class="form-range" id="poe-temperature" min="0" max="2" step="0.1" value="0.7" style="width: 100%;">
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label style="font-size: 0.8125em; color: #6b7280; margin-bottom: 4px; display: block;">Max Tokens</label>
                        <input type="number" class="form-control form-control-sm" id="poe-max-tokens" value="2000" min="1" max="8000" style="width: 100%;">
                    </div>
                    <button type="button" class="poe-btn-link" onclick="$('#poe-advanced-options').slideUp();" style="color: #6b7280; font-size: 0.8125em; border: none; background: none; cursor: pointer; padding: 8px;">
                        Hide Options
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.poe-container {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    margin: 0;
    padding: 0;
}

body.poe-page {
    overflow: hidden;
}

.poe-model-item:hover {
    background: #f3f4f6 !important;
}

.poe-model-item.selected {
    background: #eff6ff !important;
    border-color: #3b82f6 !important;
}

.poe-input-box:focus-within {
    border-color: #111827 !important;
    box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.05) !important;
}

.poe-action-btn:hover {
    background: #e5e7eb !important;
}

.poe-send-btn:hover:not(:disabled) {
    background: #374151 !important;
    transform: scale(1.05);
}

.poe-send-btn:disabled {
    background: #d1d5db !important;
    cursor: not-allowed;
}

.poe-message {
    margin-bottom: 24px;
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

.poe-message-user {
    display: flex;
    justify-content: flex-end;
}

.poe-message-ai {
    display: flex;
    justify-content: flex-start;
}

.poe-message-bubble {
    max-width: 70%;
    padding: 12px 16px;
    border-radius: 16px;
    word-wrap: break-word;
    line-height: 1.6;
    font-size: 15px;
}

.poe-message-user .poe-message-bubble {
    background: #111827;
    color: #ffffff;
    border-bottom-right-radius: 4px;
    margin-left: auto;
}

.poe-message-ai .poe-message-bubble {
    background: #f3f4f6;
    color: #111827;
    border-bottom-left-radius: 4px;
    margin-right: auto;
}

.poe-message-header {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    font-size: 0.875em;
    font-weight: 600;
}

.poe-message-content {
    white-space: pre-wrap;
    word-wrap: break-word;
}

.poe-image-preview-item {
    position: relative;
    display: inline-block;
}

.poe-image-preview-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.poe-image-preview-item .remove-image {
    position: absolute;
    top: -6px;
    right: -6px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: #ef4444;
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
}

.poe-loading {
    display: inline-flex;
    gap: 4px;
    align-items: center;
}

.poe-loading-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #9ca3af;
    animation: poe-bounce 1.4s infinite ease-in-out both;
}

.poe-loading-dot:nth-child(1) { animation-delay: -0.32s; }
.poe-loading-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes poe-bounce {
    0%, 80%, 100% {
        transform: scale(0);
    }
    40% {
        transform: scale(1);
    }
}

@media (max-width: 768px) {
    .poe-sidebar {
        position: absolute;
        left: -280px;
        z-index: 1000;
        transition: left 0.3s;
    }
    
    .poe-sidebar.open {
        left: 0;
    }
    
    .poe-message-bubble {
        max-width: 85%;
    }
}
</style>

<script>
const aiModels = <?= json_encode($ai_models) ?>;
let uploadedImages = [];
let selectedModels = {};

$(document).ready(function() {
    
    // Auto-resize textarea
    $('#poe-prompt-text').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
        updateSendButton();
    });

    // Update send button state
    function updateSendButton() {
        const hasText = $('#poe-prompt-text').val().trim().length > 0;
        const hasModels = Object.keys(selectedModels).length > 0;
        $('#poe-submit-prompt').prop('disabled', !(hasText && hasModels));
    }

    // Select model
    window.selectModel = function(aiKey) {
        const checkbox = $(`#model_${aiKey}`);
        checkbox.prop('checked', !checkbox.prop('checked'));
        updateSelectedModels();
    };

    // Update selected models
    window.updateSelectedModels = function() {
        selectedModels = {};
        $('.poe-model-checkbox:checked').each(function() {
            const aiKey = $(this).data('ai-key');
            const version = $(`.poe-version-select[data-ai-key="${aiKey}"]`).val();
            selectedModels[aiKey] = {
                provider: aiKey,
                version: version,
                model: aiModels[aiKey]
            };
        });
        
        // Update UI
        $('.poe-model-item').removeClass('selected');
        $('.poe-model-checkbox:checked').each(function() {
            $(this).closest('.poe-model-item').addClass('selected');
        });
        
        updateSendButton();
    };

    // Select all models
    $('#select-all-models').on('click', function() {
        const allChecked = $('.poe-model-checkbox:checked').length === $('.poe-model-checkbox').length;
        $('.poe-model-checkbox').prop('checked', !allChecked);
        updateSelectedModels();
        $(this).text(allChecked ? 'Select All' : 'Deselect All');
    });

    // Version change
    $('.poe-version-select').on('change', function() {
        updateSelectedModels();
    });

    // Image upload handler
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
        const preview = $('#poe-image-preview');
        preview.empty();
        if (uploadedImages.length > 0) {
            preview.show();
            uploadedImages.forEach((img, index) => {
                const item = $('<div class="poe-image-preview-item"></div>');
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
    $('#poe-temperature').on('input', function() {
        $('#poe-temp-value').text($(this).val());
    });

    // Submit prompt
    $('#poe-submit-prompt').on('click', function() {
        if ($(this).prop('disabled')) return;

        const promptText = $('#poe-prompt-text').val().trim();
        if (!promptText && uploadedImages.length === 0) {
            return;
        }

        if (Object.keys(selectedModels).length === 0) {
            alert('Please select at least one AI model.');
            return;
        }

        // Hide empty state
        $('#poe-empty-state').hide();

        // Add user message
        if (promptText) {
            const userMsg = $(`
                <div class="poe-message poe-message-user">
                    <div class="poe-message-bubble">
                        <div class="poe-message-content">${promptText.replace(/\n/g, '<br>')}</div>
                    </div>
                </div>
            `);
            $('#poe-messages-container').append(userMsg);
        }

        // Add image attachments if any
        if (uploadedImages.length > 0) {
            uploadedImages.forEach(img => {
                const imgMsg = $(`
                    <div class="poe-message poe-message-user">
                        <div class="poe-message-bubble">
                            <img src="${img.dataUrl}" style="max-width: 300px; border-radius: 8px; margin-top: 8px;">
                        </div>
                    </div>
                `);
                $('#poe-messages-container').append(imgMsg);
            });
        }

        // Process each selected AI
        Object.values(selectedModels).forEach(function(modelConfig) {
            const responseId = `response_${modelConfig.provider}_${modelConfig.version}_${Date.now()}`;
            const aiModel = modelConfig.model;
            
            const responseDiv = $(`
                <div class="poe-message poe-message-ai" id="${responseId}">
                    <div style="display: flex; gap: 12px; width: 100%;">
                        <div class="poe-model-avatar" style="width: 32px; height: 32px; border-radius: 8px; background: ${aiModel.bg_color}; display: flex; align-items: center; justify-content: center; color: ${aiModel.color}; font-size: 1em; flex-shrink: 0;">
                            ${aiModel.icon}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div class="poe-message-header" style="color: ${aiModel.color};">
                                ${aiModel.name} • ${modelConfig.version}
                            </div>
                            <div class="poe-message-bubble">
                                <div class="poe-message-content">
                                    <div class="poe-loading">
                                        <div class="poe-loading-dot"></div>
                                        <div class="poe-loading-dot"></div>
                                        <div class="poe-loading-dot"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            $('#poe-messages-container').append(responseDiv);

            // Simulate API call
            setTimeout(function() {
                const content = `This is a simulated response from ${aiModel.name} (${modelConfig.version}).\n\n` +
                              `Your prompt: "${promptText.substring(0, 100)}${promptText.length > 100 ? '...' : ''}"\n\n` +
                              `In a real implementation, this would make an API call to ${aiModel.name} with the selected model version. ` +
                              `The response would include the actual AI-generated content based on your prompt.`;
                
                $(`#${responseId} .poe-message-content`).html(`<div style="white-space: pre-wrap; font-family: inherit; margin: 0;">${content}</div>`);
            }, 1000 + Math.random() * 2000);
        });

        // Clear input
        $('#poe-prompt-text').val('').css('height', 'auto');
        uploadedImages = [];
        updateImagePreview();
        updateSendButton();

        // Scroll to bottom
        const container = $('#poe-messages-container');
        container.animate({
            scrollTop: container[0].scrollHeight
        }, 300);
    });

    // Enter key to submit (Shift+Enter for new line)
    $('#poe-prompt-text').on('keydown', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            if (!$('#poe-submit-prompt').prop('disabled')) {
                $('#poe-submit-prompt').click();
            }
        }
    });

    // Initialize
    updateSelectedModels();
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
