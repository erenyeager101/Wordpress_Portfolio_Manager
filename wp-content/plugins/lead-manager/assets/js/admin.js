/**
 * Lead Manager Pro - Admin JavaScript
 */

(function ($) {
    'use strict';

    // ============================================
    // DASHBOARD CHARTS
    // ============================================
    const DashboardCharts = {
        init() {
            this.initLeadsChart();
            this.initSourcesChart();
        },

        async initLeadsChart() {
            const ctx = document.getElementById('lmp-leads-chart');
            if (!ctx) return;

            try {
                const response = await $.ajax({
                    url: lmpAdminData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'lmp_get_stats',
                        nonce: lmpAdminData.nonce,
                    },
                });

                if (response.success && response.data.chart_data) {
                    const chartData = response.data.chart_data;

                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: chartData.map(d => this.formatDate(d.date)),
                            datasets: [{
                                label: 'Leads',
                                data: chartData.map(d => d.count),
                                borderColor: '#6366f1',
                                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: '#6366f1',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 4,
                                pointHoverRadius: 6,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false,
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    padding: 12,
                                    borderRadius: 8,
                                    titleFont: {
                                        size: 14,
                                        weight: '600',
                                    },
                                    bodyFont: {
                                        size: 13,
                                    },
                                    callbacks: {
                                        label: function (context) {
                                            return context.parsed.y + ' lead' + (context.parsed.y !== 1 ? 's' : '');
                                        },
                                    },
                                },
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        font: {
                                            size: 12,
                                        },
                                    },
                                    grid: {
                                        color: 'rgba(0, 0, 0, 0.05)',
                                    },
                                },
                                x: {
                                    ticks: {
                                        font: {
                                            size: 11,
                                        },
                                        maxRotation: 45,
                                        minRotation: 45,
                                    },
                                    grid: {
                                        display: false,
                                    },
                                },
                            },
                        },
                    });
                }
            } catch (error) {
                console.error('Error loading chart data:', error);
            }
        },

        initSourcesChart() {
            const ctx = document.getElementById('lmp-sources-chart');
            if (!ctx) return;

            // Sample data - you can make this dynamic
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Contact Form', 'Direct', 'Other'],
                    datasets: [{
                        data: [75, 15, 10],
                        backgroundColor: [
                            '#6366f1',
                            '#ec4899',
                            '#10b981',
                        ],
                        borderWidth: 0,
                    }],
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                font: {
                                    size: 12,
                                },
                                usePointStyle: true,
                            },
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            borderRadius: 8,
                            callbacks: {
                                label: function (context) {
                                    return context.label + ': ' + context.parsed + '%';
                                },
                            },
                        },
                    },
                },
            });
        },

        formatDate(dateString) {
            const date = new Date(dateString);
            const month = date.toLocaleDateString('en-US', { month: 'short' });
            const day = date.getDate();
            return `${month} ${day}`;
        },
    };

    // ============================================
    // EXPORT LEADS
    // ============================================
    const ExportLeads = {
        init() {
            $('#lmp-export-leads, #lmp-export-leads-action').on('click', (e) => {
                e.preventDefault();
                this.exportCSV();
            });
        },

        async exportCSV() {
            const $btn = $('#lmp-export-leads');
            const originalText = $btn.text();

            $btn.prop('disabled', true).html('<span class="lmp-loading"></span> Exporting...');

            try {
                // Create a temporary form to trigger download
                const form = $('<form>', {
                    method: 'POST',
                    action: lmpAdminData.ajaxUrl,
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'action',
                    value: 'lmp_export_leads',
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: 'nonce',
                    value: lmpAdminData.nonce,
                }));

                form.appendTo('body').submit().remove();

                // Show success message
                this.showNotice('success', 'Leads exported successfully!');
            } catch (error) {
                console.error('Export error:', error);
                this.showNotice('error', 'Failed to export leads. Please try again.');
            } finally {
                setTimeout(() => {
                    $btn.prop('disabled', false).text(originalText);
                }, 1000);
            }
        },

        showNotice(type, message) {
            const $notice = $('<div>', {
                class: `lmp-notice lmp-notice-${type}`,
                text: message,
            });

            $('.lmp-dashboard').prepend($notice);

            setTimeout(() => {
                $notice.fadeOut(() => $notice.remove());
            }, 3000);
        },
    };

    // ============================================
    // COPY SHORTCODE
    // ============================================
    const CopyShortcode = {
        init() {
            $('.lmp-copy-shortcode').on('click', function (e) {
                e.preventDefault();
                const shortcode = $(this).data('shortcode');

                navigator.clipboard.writeText(shortcode).then(() => {
                    const $btn = $(this);
                    const originalText = $btn.text();

                    $btn.addClass('copied').text('Copied!');

                    setTimeout(() => {
                        $btn.removeClass('copied').text(originalText);
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy:', err);
                    alert('Failed to copy shortcode. Please copy manually.');
                });
            });
        },
    };

    // ============================================
    // LEAD STATUS UPDATE
    // ============================================
    const LeadStatusUpdate = {
        init() {
            $('.lmp-status-select').on('change', function () {
                const $select = $(this);
                const postId = $select.data('post-id');
                const newStatus = $select.val();

                $.ajax({
                    url: lmpAdminData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'lmp_update_lead_status',
                        nonce: lmpAdminData.nonce,
                        post_id: postId,
                        status: newStatus,
                    },
                    success: function (response) {
                        if (response.success) {
                            $select.closest('.lmp-meta-box-field').append(
                                '<span class="lmp-status-saved" style="color: #10b981; margin-left: 10px;">✓ Saved</span>'
                            );

                            setTimeout(() => {
                                $('.lmp-status-saved').fadeOut(() => {
                                    $('.lmp-status-saved').remove();
                                });
                            }, 2000);
                        }
                    },
                });
            });
        },
    };

    // ============================================
    // REAL-TIME STATS UPDATE
    // ============================================
    const RealTimeStats = {
        init() {
            // Update stats every 60 seconds
            setInterval(() => {
                this.updateStats();
            }, 60000);
        },

        async updateStats() {
            try {
                const response = await $.ajax({
                    url: lmpAdminData.ajaxUrl,
                    type: 'POST',
                    data: {
                        action: 'lmp_get_stats',
                        nonce: lmpAdminData.nonce,
                    },
                });

                if (response.success) {
                    const stats = response.data;

                    // Update stat cards with animation
                    this.animateValue('.lmp-stat-card--primary .lmp-stat-value', stats.total);
                    this.animateValue('.lmp-stat-card--success .lmp-stat-value', stats.this_month);
                    this.animateValue('.lmp-stat-card--info .lmp-stat-value', stats.this_week);
                    this.animateValue('.lmp-stat-card--warning .lmp-stat-value', stats.today);
                }
            } catch (error) {
                console.error('Error updating stats:', error);
            }
        },

        animateValue(selector, newValue) {
            const $element = $(selector);
            const currentValue = parseInt($element.text()) || 0;

            if (currentValue === newValue) return;

            const duration = 500;
            const steps = 20;
            const stepValue = (newValue - currentValue) / steps;
            const stepDuration = duration / steps;

            let currentStep = 0;

            const interval = setInterval(() => {
                currentStep++;
                const value = Math.round(currentValue + (stepValue * currentStep));
                $element.text(value);

                if (currentStep >= steps) {
                    clearInterval(interval);
                    $element.text(newValue);
                }
            }, stepDuration);
        },
    };

    // ============================================
    // DASHBOARD SEARCH & FILTER
    // ============================================
    const DashboardFilter = {
        init() {
            // Add search box to leads table
            if ($('.lmp-leads-table').length) {
                this.addSearchBox();
            }
        },

        addSearchBox() {
            const $searchBox = $('<div>', {
                class: 'lmp-search-box',
                html: '<input type="text" placeholder="Search leads..." class="lmp-search-input" />',
            });

            $('.lmp-leads-table').before($searchBox);

            $('.lmp-search-input').on('input', function () {
                const searchTerm = $(this).val().toLowerCase();

                $('.lmp-leads-table tbody tr').each(function () {
                    const text = $(this).text().toLowerCase();
                    $(this).toggle(text.includes(searchTerm));
                });
            });
        },
    };

    // ============================================
    // KEYBOARD SHORTCUTS
    // ============================================
    const KeyboardShortcuts = {
        init() {
            $(document).on('keydown', (e) => {
                // Ctrl/Cmd + E = Export
                if ((e.ctrlKey || e.metaKey) && e.key === 'e') {
                    e.preventDefault();
                    $('#lmp-export-leads').click();
                }

                // Ctrl/Cmd + / = Search
                if ((e.ctrlKey || e.metaKey) && e.key === '/') {
                    e.preventDefault();
                    $('.lmp-search-input').focus();
                }
            });
        },
    };

    // ============================================
    // INITIALIZE ALL
    // ============================================
    $(document).ready(function () {
        if ($('.lmp-dashboard').length) {
            DashboardCharts.init();
            ExportLeads.init();
            CopyShortcode.init();
            LeadStatusUpdate.init();
            RealTimeStats.init();
            DashboardFilter.init();
            KeyboardShortcuts.init();
        }
    });

})(jQuery);
