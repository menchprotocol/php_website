<?php

//TITLE
$website_id = website_setting(0);
$expanded_space = in_array($website_id , $this->config->item('userids___31025'));

if(in_array($website_id, $this->config->item('userids___30984'))){
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'homecss_black_font\'); }); </script> ';
} else {
    echo ' <script> $(document).ready(function () { $(\'body\').addClass(\'homecss_white_font\'); }); </script> ';
}

// Set page title
echo ' <script> $(document).ready(function () { $(document).prop(\'title\', \''.get_domain('m__name').' | AI Prompt Interface\'); }); </script> ';

// Available AI Models Configuration
$ai_models = array(
    'openai' => array(
        'name' => 'OpenAI',
        'icon' => '<i class="fas fa-robot"></i>',
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
        'versions' => array(
            'gemini-1.5-pro' => 'Gemini 1.5 Pro (Latest)',
            'gemini-1.5-flash' => 'Gemini 1.5 Flash',
            'gemini-pro' => 'Gemini Pro',
        )
    ),
    'meta' => array(
        'name' => 'Meta Llama',
        'icon' => '<i class="fas fa-code"></i>',
        'versions' => array(
            'llama-3-70b' => 'Llama 3 70B (Latest)',
            'llama-3-8b' => 'Llama 3 8B',
            'llama-2-70b' => 'Llama 2 70B',
        )
    ),
);

?>

<div class="prompt-container" style="margin: <?= $expanded_space ? '144px auto 89px' : '89px auto 89px' ?>; max-width: 1200px; padding: 0 20px;">
    
    <h1 class="text-center" style="margin-bottom: 40px;">AI Prompt Interface</h1>
    
    <!-- AI Selection Panel -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-sliders-h"></i> Select AI Models</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <?php foreach($ai_models as $ai_key => $ai_model): ?>
                <div class="col-md-6 col-lg-3 mb-3">
                    <div class="form-check ai-model-checkbox">
                        <input class="form-check-input ai-model-select" type="checkbox" 
                               value="<?= $ai_key ?>" id="ai_<?= $ai_key ?>" 
                               data-ai-key="<?= $ai_key ?>">
                        <label class="form-check-label" for="ai_<?= $ai_key ?>">
                            <div class="ai-model-card">
                                <div class="ai-icon"><?= $ai_model['icon'] ?></div>
                                <div class="ai-name"><?= $ai_model['name'] ?></div>
                            </div>
                        </label>
                    </div>
                    <!-- Version Selector (shown when AI is selected) -->
                    <div class="ai-version-selector mt-2" id="version_<?= $ai_key ?>" style="display: none;">
                        <select class="form-select form-select-sm ai-version-select" 
                                data-ai-key="<?= $ai_key ?>" multiple>
                            <?php foreach($ai_model['versions'] as $version_key => $version_name): ?>
                            <option value="<?= $version_key ?>"><?= $version_name ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple versions</small>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="mt-3">
                <button type="button" class="btn btn-sm btn-outline-primary" id="select-all-ais">
                    <i class="fas fa-check-double"></i> Select All AIs
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all-ais">
                    <i class="fas fa-times"></i> Deselect All
                </button>
            </div>
        </div>
    </div>

    <!-- Multi-Modal Prompt Interface -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="mb-0"><i class="fas fa-comments"></i> Multi-Modal Prompt</h3>
        </div>
        <div class="card-body">
            
            <!-- Text Input -->
            <div class="mb-4">
                <label for="prompt-text" class="form-label">
                    <i class="fas fa-keyboard"></i> Text Prompt
                </label>
                <textarea class="form-control" id="prompt-text" rows="6" 
                          placeholder="Enter your prompt here... You can ask questions, request content generation, analysis, or any other AI task."></textarea>
                <div class="form-text">
                    <span id="char-count">0</span> characters
                </div>
            </div>

            <!-- Image Upload -->
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-image"></i> Image Input (Optional)
                </label>
                <div class="input-group">
                    <input type="file" class="form-control" id="prompt-image" 
                           accept="image/*" multiple>
                    <button class="btn btn-outline-secondary" type="button" id="clear-images">
                        <i class="fas fa-trash"></i> Clear
                    </button>
                </div>
                <div class="form-text">You can upload multiple images for vision-based AI models</div>
                <div id="image-preview" class="mt-3"></div>
            </div>

            <!-- Audio Upload -->
            <div class="mb-4">
                <label class="form-label">
                    <i class="fas fa-microphone"></i> Audio Input (Optional)
                </label>
                <div class="input-group">
                    <input type="file" class="form-control" id="prompt-audio" 
                           accept="audio/*">
                    <button class="btn btn-outline-secondary" type="button" id="record-audio">
                        <i class="fas fa-record-vinyl"></i> Record
                    </button>
                </div>
                <div class="form-text">Upload audio files or record directly</div>
                <div id="audio-preview" class="mt-3"></div>
            </div>

            <!-- Advanced Options -->
            <div class="accordion mb-4" id="advancedOptions">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" 
                                data-bs-toggle="collapse" data-bs-target="#collapseAdvanced">
                            <i class="fas fa-cog"></i> Advanced Options
                        </button>
                    </h2>
                    <div id="collapseAdvanced" class="accordion-collapse collapse" 
                         data-bs-parent="#advancedOptions">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="temperature" class="form-label">
                                        Temperature: <span id="temp-value">0.7</span>
                                    </label>
                                    <input type="range" class="form-range" id="temperature" 
                                           min="0" max="2" step="0.1" value="0.7">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="max-tokens" class="form-label">Max Tokens</label>
                                    <input type="number" class="form-control" id="max-tokens" 
                                           value="2000" min="1" max="8000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="system-prompt" class="form-label">System Prompt (Optional)</label>
                                    <textarea class="form-control" id="system-prompt" rows="3" 
                                              placeholder="Define the AI's role or behavior..."></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Response Format</label>
                                    <select class="form-select" id="response-format">
                                        <option value="text">Plain Text</option>
                                        <option value="markdown">Markdown</option>
                                        <option value="json">JSON</option>
                                        <option value="html">HTML</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-secondary" id="clear-prompt">
                    <i class="fas fa-eraser"></i> Clear All
                </button>
                <button type="button" class="btn btn-primary btn-lg" id="submit-prompt">
                    <i class="fas fa-paper-plane"></i> Send to Selected AIs
                </button>
            </div>
        </div>
    </div>

    <!-- Response Area -->
    <div class="card mt-4" id="response-card" style="display: none;">
        <div class="card-header bg-info text-white">
            <h3 class="mb-0"><i class="fas fa-reply"></i> AI Responses</h3>
        </div>
        <div class="card-body" id="response-container">
            <!-- Responses will be dynamically inserted here -->
        </div>
    </div>

</div>

<style>
.prompt-container {
    font-family: inherit;
}

.ai-model-card {
    padding: 15px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    text-align: center;
    transition: all 0.3s ease;
    cursor: pointer;
}

.ai-model-card:hover {
    border-color: #007bff;
    background-color: #f8f9fa;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.form-check-input:checked ~ .form-check-label .ai-model-card {
    border-color: #007bff;
    background-color: #e7f3ff;
}

.ai-icon {
    font-size: 2em;
    margin-bottom: 10px;
    color: #007bff;
}

.ai-name {
    font-weight: 600;
    font-size: 0.95em;
}

.ai-version-selector {
    margin-top: 10px;
}

.ai-version-select {
    font-size: 0.85em;
}

#image-preview, #audio-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.image-preview-item {
    position: relative;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 5px;
}

.image-preview-item img {
    max-width: 150px;
    max-height: 150px;
    border-radius: 4px;
}

.image-preview-item .remove-image {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    cursor: pointer;
    font-size: 12px;
}

.audio-preview-item {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #f8f9fa;
}

.ai-response {
    border-left: 4px solid #007bff;
    padding: 15px;
    margin-bottom: 15px;
    background: #f8f9fa;
    border-radius: 4px;
}

.ai-response-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 10px;
    padding-bottom: 10px;
    border-bottom: 1px solid #dee2e6;
}

.ai-response-title {
    font-weight: 600;
    color: #007bff;
}

.ai-response-time {
    font-size: 0.85em;
    color: #6c757d;
}

.ai-response-content {
    white-space: pre-wrap;
    word-wrap: break-word;
}

.loading-spinner {
    text-align: center;
    padding: 20px;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border: none;
}

.card-header {
    border-bottom: 2px solid rgba(255,255,255,0.2);
}

@media (max-width: 768px) {
    .prompt-container {
        margin: 60px auto 40px !important;
        padding: 0 15px;
    }
    
    .ai-model-card {
        padding: 10px;
    }
    
    .ai-icon {
        font-size: 1.5em;
    }
}
</style>

<script>
$(document).ready(function() {
    
    // Character counter
    $('#prompt-text').on('input', function() {
        $('#char-count').text($(this).val().length);
    });

    // Show/hide version selector when AI is selected
    $('.ai-model-select').on('change', function() {
        const aiKey = $(this).data('ai-key');
        const versionSelector = $('#version_' + aiKey);
        if ($(this).is(':checked')) {
            versionSelector.slideDown(200);
        } else {
            versionSelector.slideUp(200);
        }
    });

    // Select All AIs
    $('#select-all-ais').on('click', function() {
        $('.ai-model-select').prop('checked', true).trigger('change');
    });

    // Deselect All AIs
    $('#deselect-all-ais').on('click', function() {
        $('.ai-model-select').prop('checked', false).trigger('change');
    });

    // Image preview
    let uploadedImages = [];
    $('#prompt-image').on('change', function(e) {
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
    });

    function updateImagePreview() {
        const preview = $('#image-preview');
        preview.empty();
        uploadedImages.forEach((img, index) => {
            const item = $('<div class="image-preview-item"></div>');
            item.append(`<img src="${img.dataUrl}" alt="Preview ${index + 1}">`);
            item.append(`<button type="button" class="remove-image" data-index="${index}"><i class="fas fa-times"></i></button>`);
            preview.append(item);
        });
    }

    $(document).on('click', '.remove-image', function() {
        const index = $(this).data('index');
        uploadedImages.splice(index, 1);
        updateImagePreview();
    });

    $('#clear-images').on('click', function() {
        uploadedImages = [];
        $('#prompt-image').val('');
        updateImagePreview();
    });

    // Audio preview
    let uploadedAudio = null;
    $('#prompt-audio').on('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('audio/')) {
            uploadedAudio = file;
            const preview = $('#audio-preview');
            preview.html(`
                <div class="audio-preview-item">
                    <i class="fas fa-file-audio"></i> ${file.name} 
                    (${(file.size / 1024).toFixed(2)} KB)
                    <button type="button" class="btn btn-sm btn-danger ms-2" id="remove-audio">
                        <i class="fas fa-times"></i> Remove
                    </button>
                </div>
            `);
        }
    });

    $(document).on('click', '#remove-audio', function() {
        uploadedAudio = null;
        $('#prompt-audio').val('');
        $('#audio-preview').empty();
    });

    // Temperature slider
    $('#temperature').on('input', function() {
        $('#temp-value').text($(this).val());
    });

    // Clear all
    $('#clear-prompt').on('click', function() {
        $('#prompt-text').val('');
        $('#char-count').text('0');
        $('#prompt-image').val('');
        $('#prompt-audio').val('');
        uploadedImages = [];
        uploadedAudio = null;
        $('#image-preview').empty();
        $('#audio-preview').empty();
        $('#system-prompt').val('');
        $('#temperature').val(0.7).trigger('input');
        $('#max-tokens').val(2000);
        $('#response-format').val('text');
        $('#response-card').hide();
        $('#response-container').empty();
    });

    // Submit prompt
    $('#submit-prompt').on('click', function() {
        const selectedAIs = [];
        $('.ai-model-select:checked').each(function() {
            const aiKey = $(this).data('ai-key');
            const versions = [];
            $(`#version_${aiKey} .ai-version-select option:selected`).each(function() {
                versions.push($(this).val());
            });
            
            // If no versions selected, use all versions
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

        const promptText = $('#prompt-text').val().trim();
        if (!promptText && uploadedImages.length === 0 && !uploadedAudio) {
            alert('Please enter a prompt or upload an image/audio.');
            return;
        }

        // Show response area
        $('#response-card').show();
        $('#response-container').empty();

        // Process each selected AI
        selectedAIs.forEach(function(aiConfig) {
            aiConfig.versions.forEach(function(version) {
                const responseId = `response_${aiConfig.provider}_${version}_${Date.now()}`;
                const responseDiv = $(`
                    <div class="ai-response" id="${responseId}">
                        <div class="ai-response-header">
                            <div class="ai-response-title">
                                ${aiConfig.provider.toUpperCase()} - ${version}
                            </div>
                            <div class="ai-response-time">
                                <i class="fas fa-clock"></i> Processing...
                            </div>
                        </div>
                        <div class="ai-response-content">
                            <div class="loading-spinner">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Sending request to ${aiConfig.provider}...</p>
                            </div>
                        </div>
                    </div>
                `);
                $('#response-container').append(responseDiv);

                // Simulate API call (replace with actual API integration)
                setTimeout(function() {
                    const content = `This is a simulated response from ${aiConfig.provider} (${version}).\n\n` +
                                  `Your prompt: "${promptText.substring(0, 100)}${promptText.length > 100 ? '...' : ''}"\n\n` +
                                  `In a real implementation, this would make an API call to ${aiConfig.provider} with the selected model version. ` +
                                  `The response would include the actual AI-generated content based on your prompt.`;
                    
                    $(`#${responseId} .ai-response-content`).html(`<pre class="ai-response-content">${content}</pre>`);
                    $(`#${responseId} .ai-response-time`).html(`<i class="fas fa-check-circle text-success"></i> Completed at ${new Date().toLocaleTimeString()}`);
                }, 1000 + Math.random() * 2000);
            });
        });

        // Scroll to response area
        $('html, body').animate({
            scrollTop: $('#response-card').offset().top - 100
        }, 500);
    });

    // Initialize tooltips if Bootstrap tooltips are available
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
});
</script>

<?php

//SOCIAL FOOTER (similar to home.php)
$domain_phone =  website_setting(28615);
$email_domain =  website_setting(28614);
$users___11035 = $this->config->item('users___11035');

$contact_us = '';
if($domain_phone || $email_domain) {
    $contact_us .= '<ul class="social-footer">';
    if($domain_phone){
        $contact_us .= '<li><a href="tel:'.preg_replace("/[^0-9]/", "", $domain_phone).'" data-toggle="tooltip" data-placement="top" title="'.$users___11035[28615]['m__name'].'">'.$users___11035[28615]['m__cover'].' '.$domain_phone.'</a></li>';
    }
    if($email_domain){
        $contact_us .= '<li><a href="mailto:'.$email_domain.'" title="'.$users___11035[28614]['m__name'].'" data-toggle="tooltip" data-placement="top">'.$users___11035[28614]['m__cover'].' '.$email_domain.'</a></li>';
    }
    $contact_us .= '</ul>';
}

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

