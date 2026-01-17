/**
 * Advanced Lead Manager Pro - Frontend JavaScript
 */

(function ($) {
    'use strict';

    // ============================================
    // FORM VALIDATION
    // ============================================
    const FormValidator = {
        rules: {
            name: {
                required: true,
                minLength: 2,
                maxLength: 100,
            },
            email: {
                required: true,
                email: true,
            },
            phone: {
                pattern: /^[\d\s\-\+\(\)]+$/,
            },
            message: {
                required: true,
                minLength: 10,
                maxLength: 2000,
            },
        },

        validate(field, value) {
            const fieldName = field.attr('name').replace('lmp_', '');
            const rules = this.rules[fieldName];

            if (!rules) return { valid: true };

            // Required check
            if (rules.required && !value.trim()) {
                return {
                    valid: false,
                    message: `${this.getFieldLabel(field)} is required.`,
                };
            }

            // Skip other validations if field is empty and not required
            if (!value.trim() && !rules.required) {
                return { valid: true };
            }

            // Min length
            if (rules.minLength && value.length < rules.minLength) {
                return {
                    valid: false,
                    message: `${this.getFieldLabel(field)} must be at least ${rules.minLength} characters.`,
                };
            }

            // Max length
            if (rules.maxLength && value.length > rules.maxLength) {
                return {
                    valid: false,
                    message: `${this.getFieldLabel(field)} must not exceed ${rules.maxLength} characters.`,
                };
            }

            // Email validation
            if (rules.email) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) {
                    return {
                        valid: false,
                        message: 'Please enter a valid email address.',
                    };
                }
            }

            // Pattern validation
            if (rules.pattern && !rules.pattern.test(value)) {
                return {
                    valid: false,
                    message: `${this.getFieldLabel(field)} format is invalid.`,
                };
            }

            return { valid: true };
        },

        getFieldLabel(field) {
            const label = field.closest('.lmp-form-group').find('label').first().text();
            return label.replace('*', '').trim();
        },

        showError(field, message) {
            field.addClass('invalid').removeClass('valid');
            field.siblings('.lmp-error-message').text(message).show();
        },

        showSuccess(field) {
            field.addClass('valid').removeClass('invalid');
            field.siblings('.lmp-error-message').hide();
        },

        clearValidation(field) {
            field.removeClass('valid invalid');
            field.siblings('.lmp-error-message').hide();
        },
    };

    // ============================================
    // FILE UPLOAD HANDLER
    // ============================================
    const FileUploadHandler = {
        maxSize: lmpData.maxFileSize || 5 * 1024 * 1024,
        allowedTypes: lmpData.allowedFileTypes || ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'],

        init() {
            const $fileInput = $('#lmp_attachment');
            const $wrapper = $('.lmp-file-upload-wrapper');
            const $label = $wrapper.find('.lmp-file-label');
            const $preview = $wrapper.find('.lmp-file-preview');

            // File input change
            $fileInput.on('change', (e) => {
                const file = e.target.files[0];
                if (file) {
                    this.handleFile(file, $wrapper, $preview);
                }
            });

            // Drag and drop
            $label.on('dragover', (e) => {
                e.preventDefault();
                $label.addClass('dragover');
            });

            $label.on('dragleave', () => {
                $label.removeClass('dragover');
            });

            $label.on('drop', (e) => {
                e.preventDefault();
                $label.removeClass('dragover');

                const file = e.originalEvent.dataTransfer.files[0];
                if (file) {
                    $fileInput[0].files = e.originalEvent.dataTransfer.files;
                    this.handleFile(file, $wrapper, $preview);
                }
            });

            // Remove file
            $wrapper.on('click', '.lmp-file-remove', () => {
                $fileInput.val('');
                $preview.hide();
                $label.show();
                $fileInput.siblings('.lmp-error-message').hide();
            });
        },

        handleFile(file, $wrapper, $preview) {
            // Validate file size
            if (file.size > this.maxSize) {
                this.showError($wrapper, lmpData.messages.fileTooBig);
                return;
            }

            // Validate file type
            const extension = file.name.split('.').pop().toLowerCase();
            if (!this.allowedTypes.includes(extension)) {
                this.showError($wrapper, lmpData.messages.invalidFileType);
                return;
            }

            // Show preview
            $wrapper.find('.lmp-file-label').hide();
            $preview.find('.lmp-file-name').text(file.name);
            $preview.show();
            $wrapper.find('.lmp-error-message').hide();
        },

        showError($wrapper, message) {
            $wrapper.find('#lmp_attachment').val('');
            $wrapper.find('.lmp-error-message').text(message).show();
        },
    };

    // ============================================
    // CHARACTER COUNTER
    // ============================================
    const CharacterCounter = {
        init() {
            const $textarea = $('#lmp_message');
            const $counter = $('.lmp-char-count');
            const maxLength = parseInt($textarea.attr('maxlength')) || 2000;

            $textarea.on('input', function () {
                const length = $(this).val().length;
                $counter.text(length);

                if (length > maxLength * 0.9) {
                    $counter.css('color', 'var(--color-secondary)');
                } else {
                    $counter.css('color', 'var(--color-primary)');
                }
            });
        },
    };

    // ============================================
    // FORM SUBMISSION
    // ============================================
    const FormSubmission = {
        init() {
            const $form = $('#lmp-lead-form');

            $form.on('submit', (e) => {
                e.preventDefault();
                this.submit($form);
            });
        },

        async submit($form) {
            // Validate all fields
            let isValid = true;
            $form.find('.lmp-input[required]').each(function () {
                const $field = $(this);
                const value = $field.val();
                const validation = FormValidator.validate($field, value);

                if (!validation.valid) {
                    FormValidator.showError($field, validation.message);
                    isValid = false;
                } else {
                    FormValidator.showSuccess($field);
                }
            });

            if (!isValid) {
                this.showMessage('error', lmpData.messages.validationError);
                return;
            }

            // Show loading state
            this.setLoading(true);

            // Prepare form data
            const formData = new FormData($form[0]);
            formData.append('action', 'lmp_submit_lead');
            formData.append('nonce', lmpData.nonce);

            // Convert to object for AJAX (except file)
            const data = {};
            for (let [key, value] of formData.entries()) {
                if (key !== 'lmp_attachment') {
                    data[key] = value;
                }
            }

            try {
                // If there's a file, use FormData, otherwise use regular data
                const hasFile = $form.find('#lmp_attachment')[0].files.length > 0;

                const response = await $.ajax({
                    url: lmpData.ajaxUrl,
                    type: 'POST',
                    data: hasFile ? formData : data,
                    processData: !hasFile,
                    contentType: hasFile ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
                    dataType: 'json',
                });

                if (response.success) {
                    this.showMessage('success', response.data.message);
                    $form[0].reset();
                    FormValidator.clearValidation($form.find('.lmp-input'));
                    $('.lmp-file-preview').hide();
                    $('.lmp-file-label').show();

                    // Track conversion (if analytics is available)
                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'form_submission', {
                            event_category: 'engagement',
                            event_label: 'contact_form',
                        });
                    }
                } else {
                    this.showMessage('error', response.data.message || lmpData.messages.error);
                }
            } catch (error) {
                console.error('Form submission error:', error);
                this.showMessage('error', lmpData.messages.error);
            } finally {
                this.setLoading(false);
            }
        },

        setLoading(loading) {
            const $form = $('#lmp-lead-form');
            const $btn = $form.find('.lmp-submit-btn');

            if (loading) {
                $form.addClass('loading');
                $btn.addClass('loading');
                $btn.find('.lmp-btn-text').hide();
                $btn.find('.lmp-btn-loader').show();
                $btn.prop('disabled', true);
            } else {
                $form.removeClass('loading');
                $btn.removeClass('loading');
                $btn.find('.lmp-btn-text').show();
                $btn.find('.lmp-btn-loader').hide();
                $btn.prop('disabled', false);
            }
        },

        showMessage(type, message) {
            const $message = $('.lmp-message');
            $message
                .removeClass('success error info')
                .addClass(type)
                .text(message)
                .show()
                .get(0).scrollIntoView({ behavior: 'smooth', block: 'nearest' });

            // Auto-hide success messages
            if (type === 'success') {
                setTimeout(() => {
                    $message.fadeOut();
                }, 5000);
            }
        },
    };

    // ============================================
    // REAL-TIME VALIDATION
    // ============================================
    const RealTimeValidation = {
        init() {
            $('.lmp-input').on('blur', function () {
                const $field = $(this);
                const value = $field.val();

                if ($field.prop('required') || value.trim()) {
                    const validation = FormValidator.validate($field, value);

                    if (!validation.valid) {
                        FormValidator.showError($field, validation.message);
                    } else {
                        FormValidator.showSuccess($field);
                    }
                }
            });

            // Clear validation on focus
            $('.lmp-input').on('focus', function () {
                const $field = $(this);
                $field.siblings('.lmp-error-message').hide();
            });
        },
    };

    // ============================================
    // FORM ANIMATIONS
    // ============================================
    const FormAnimations = {
        init() {
            // Animate form groups on scroll
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('lmp-fade-in');
                        }, index * 100);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.1,
            });

            document.querySelectorAll('.lmp-form-group').forEach((el) => {
                observer.observe(el);
            });

            // Input focus animations
            $('.lmp-input').on('focus', function () {
                $(this).closest('.lmp-form-group').addClass('focused');
            });

            $('.lmp-input').on('blur', function () {
                if (!$(this).val()) {
                    $(this).closest('.lmp-form-group').removeClass('focused');
                }
            });
        },
    };

    // ============================================
    // AUTO-SAVE DRAFT (LocalStorage)
    // ============================================
    const AutoSaveDraft = {
        storageKey: 'lmp_form_draft',

        init() {
            // Load draft on page load
            this.loadDraft();

            // Save draft on input
            $('.lmp-input').on('input change', () => {
                this.saveDraft();
            });

            // Clear draft on successful submission
            $(document).on('lmp_form_success', () => {
                this.clearDraft();
            });
        },

        saveDraft() {
            const draft = {};
            $('.lmp-input').each(function () {
                const $field = $(this);
                const name = $field.attr('name');
                if (name && $field.attr('type') !== 'file') {
                    draft[name] = $field.val();
                }
            });

            localStorage.setItem(this.storageKey, JSON.stringify(draft));
        },

        loadDraft() {
            const draft = localStorage.getItem(this.storageKey);
            if (draft) {
                try {
                    const data = JSON.parse(draft);
                    Object.keys(data).forEach((name) => {
                        const $field = $(`[name="${name}"]`);
                        if ($field.length && data[name]) {
                            $field.val(data[name]);
                            $field.closest('.lmp-form-group').addClass('focused');
                        }
                    });
                } catch (e) {
                    console.error('Error loading draft:', e);
                }
            }
        },

        clearDraft() {
            localStorage.removeItem(this.storageKey);
        },
    };

    // ============================================
    // ACCESSIBILITY ENHANCEMENTS
    // ============================================
    const AccessibilityEnhancements = {
        init() {
            // Add ARIA labels
            $('.lmp-input').each(function () {
                const $field = $(this);
                const label = $field.closest('.lmp-form-group').find('label').text();
                $field.attr('aria-label', label);
            });

            // Keyboard navigation for file upload
            $('.lmp-file-label').on('keypress', function (e) {
                if (e.which === 13 || e.which === 32) {
                    e.preventDefault();
                    $(this).prev('input[type="file"]').click();
                }
            });
        },
    };

    // ============================================
    // INITIALIZE ALL
    // ============================================
    $(document).ready(function () {
        if ($('#lmp-lead-form').length) {
            FileUploadHandler.init();
            CharacterCounter.init();
            FormSubmission.init();
            RealTimeValidation.init();
            FormAnimations.init();
            AutoSaveDraft.init();
            AccessibilityEnhancements.init();
        }
    });

})(jQuery);
