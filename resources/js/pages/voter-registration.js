/**
 * Voter Registration Frontend API Integration
 * Handles category listing, application submission, and status tracking
 */

// Get CSRF token from meta tag
const getCsrfToken = () => {
    return document.querySelector('meta[name="csrf-token"]')?.content || 
           document.querySelector('meta[name="X-CSRF-TOKEN"]')?.content || '';
};

// Get auth token from localStorage or meta tag (Sanctum)
const getAuthToken = () => {
    return localStorage.getItem('auth-token') || 
           document.querySelector('meta[name="X-Auth-Token"]')?.content || '';
};

// API Base URL
const API_BASE = '/api';

// ========== UTILITY FUNCTIONS ==========

/**
 * Make an API request
 */
async function apiRequest(endpoint, options = {}) {
    const url = `${API_BASE}${endpoint}`;
    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...options.headers,
    };

    // Add auth token if available
    const token = getAuthToken();
    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    try {
        const response = await fetch(url, {
            ...options,
            headers,
        });

        const data = await response.json();

        if (!response.ok) {
            throw {
                status: response.status,
                ...data,
            };
        }

        return data;
    } catch (error) {
        console.error('API Error:', error);
        throw error;
    }
}

/**
 * Show a notification (success/error/info)
 */
function showNotification(message, type = 'info') {
    const notif = document.createElement('div');
    const bgColor = {
        success: 'bg-green-50 border-green-200 text-green-700',
        error: 'bg-red-50 border-red-200 text-red-700',
        info: 'bg-blue-50 border-blue-200 text-blue-700',
    }[type];

    notif.className = `fixed top-6 right-6 p-4 rounded-xl border ${bgColor} shadow-lg z-50 max-w-md`;
    notif.innerHTML = `
        <div class="flex items-center gap-3">
            <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
            <p>${message}</p>
        </div>
    `;

    document.body.appendChild(notif);

    // Auto-remove after 4 seconds
    setTimeout(() => notif.remove(), 4000);
}

/**
 * Format date for display
 */
function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' });
}

// ========== VOTER REGISTRATION PAGE INITIALIZATION ==========

async function initializeVoterRegistration() {
    console.log('Initializing Voter Registration page...');

    try {
        // Load categories and applications in parallel
        const [categoriesData, applicationsData] = await Promise.all([
            apiRequest('/categories'),
            apiRequest('/applications'),
        ]);

        renderCategories(categoriesData.data || []);
        renderApplications(applicationsData.data || []);
        renderHistory(applicationsData.data || []);

    } catch (error) {
        console.error('Failed to initialize voter registration:', error);
        showNotification('Failed to load data. Please refresh the page.', 'error');
    }
}

// ========== RENDER CATEGORIES ==========

function renderCategories(categories) {
    const grid = document.getElementById('categoriesGrid');
    if (!grid) return;

    grid.innerHTML = '';

    if (categories.length === 0) {
        grid.innerHTML = `
            <div class="col-span-full py-12 text-center text-gray-500">
                <i class="fas fa-calendar-times text-gray-300 text-4xl mb-4"></i>
                <p>No election categories available at the moment.</p>
            </div>
        `;
        return;
    }

    categories.forEach(category => {
        const card = document.createElement('div');
        card.className = 'border border-gray-200 rounded-2xl p-6 hover:border-primary transition hover:shadow-md bg-white shadow-sm';
        card.innerHTML = `
            <div class="flex items-start justify-between mb-4">
                <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-primary text-2xl">
                    <i class="fas ${category.icon}"></i>
                </div>
                <span id="badge-${category.id}" class="hidden bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-semibold">
                    Applied
                </span>
            </div>
            <h4 class="font-bold text-gray-900 text-lg mb-2">${category.name}</h4>
            <p class="text-sm text-gray-600 mb-4">${category.description}</p>
            <div class="flex items-center text-xs text-gray-500 mb-6 gap-4">
                <span><i class="fas fa-calendar mr-1"></i>${category.deadline}</span>
                <span><i class="fas fa-vote-yea mr-1"></i>${category.votes_count} votes</span>
            </div>
            <button class="apply-btn w-full bg-primary text-white py-2.5 rounded-lg font-bold hover:bg-primary-dark transition-colors text-sm disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed" 
                    data-category-id="${category.id}"
                    data-category-name="${category.name}"
                    data-category-deadline="${category.deadline}">
                Apply now
            </button>
        `;

        grid.appendChild(card);
    });

    // Attach click listeners to apply buttons
    attachApplyButtonListeners();
}

// ========== RENDER MY APPLICATIONS ==========

function renderApplications(applications) {
    const list = document.getElementById('applicationsList');
    const empty = document.getElementById('emptyApplications');

    if (!list || !empty) return;

    // Mark applied categories in the grid
    const appliedCategoryIds = applications.map(app => app.category_id);
    appliedCategoryIds.forEach(id => {
        const badge = document.getElementById(`badge-${id}`);
        const button = document.querySelector(`[data-category-id="${id}"]`);
        if (badge) badge.classList.remove('hidden');
        if (button) {
            button.disabled = true;
            button.textContent = '✓ Applied';
            button.classList.remove('bg-primary', 'hover:bg-primary-dark');
            button.classList.add('bg-gray-100', 'text-gray-400');
        }
    });

    if (applications.length === 0) {
        list.innerHTML = '';
        empty.style.display = 'block';
        return;
    }

    empty.style.display = 'none';
    list.innerHTML = '';

    applications.forEach((app, index) => {
        const statusColors = {
            pending: { bg: 'bg-orange-50', border: 'border-orange-200', text: 'text-orange-700', label: 'Pending' },
            approved: { bg: 'bg-green-50', border: 'border-green-200', text: 'text-green-700', label: 'Approved' },
            rejected: { bg: 'bg-red-50', border: 'border-red-200', text: 'text-red-700', label: 'Rejected' },
            registered: { bg: 'bg-blue-50', border: 'border-blue-200', text: 'text-blue-700', label: 'Registered' },
        };
        const colors = statusColors[app.status] || statusColors.pending;

        const appCard = document.createElement('div');
        appCard.className = `border border-gray-200 rounded-2xl p-6 flex items-center justify-between ${colors.bg} ${colors.border} bg-white transition`;
        appCard.id = `app-${app.id}`;
        appCard.innerHTML = `
            <div class="flex-1">
                <h4 class="font-bold text-gray-900">${app.category_name}</h4>
                <p class="text-sm text-gray-600 mt-1">Applied: ${app.submitted_date}</p>
                <p class="text-xs text-gray-500 mt-1 italic">"${app.statement.substring(0, 60)}..."</p>
                ${app.can_withdraw ? `
                    <button class="mt-3 text-xs text-orange-600 hover:text-orange-700 font-semibold withdraw-btn" data-app-id="${app.id}">
                        <i class="fas fa-trash-alt mr-1"></i> Withdraw
                    </button>
                ` : ''}
            </div>
            <span class="${colors.bg} ${colors.text} border ${colors.border} px-4 py-2 rounded-lg font-bold text-sm whitespace-nowrap ml-4">
                ${colors.label}
            </span>
        `;

        list.appendChild(appCard);
    });

    // Attach withdraw listeners
    attachWithdrawListeners();
}

// ========== RENDER APPLICATION HISTORY ==========

function renderHistory(applications) {
    const timeline = document.getElementById('historyTimeline');
    const empty = document.getElementById('emptyHistory');

    if (!timeline || !empty) return;

    // Filter for registered applications (which form the history)
    const registered = applications.filter(app => app.status === 'registered');

    if (registered.length === 0) {
        timeline.innerHTML = '';
        empty.style.display = 'block';
        return;
    }

    empty.style.display = 'none';
    timeline.innerHTML = registered.map(item => `
        <div class="flex gap-4">
            <div class="relative">
                <div class="w-3 h-3 bg-primary rounded-full mt-2"></div>
                <div class="absolute left-1/2 top-5 w-0.5 h-12 bg-gray-200 -translate-x-1/2"></div>
            </div>
            <div>
                <p class="font-bold text-gray-900">${item.category_name}</p>
                <p class="text-xs text-gray-500">${new Date(item.reviewed_at).getFullYear()} · 
                    <span class="px-2 py-1 rounded-full text-[10px] font-bold bg-green-100 text-green-700">
                        Registered
                    </span>
                </p>
            </div>
        </div>
    `).join('');
}

// ========== MODAL AND FORM HANDLING ==========

function setupModalHandling() {
    const modal = document.getElementById('applicationModal');
    const form = document.getElementById('applicationForm');
    const motivationInput = document.getElementById('motivationInput');
    const charCount = document.getElementById('charCount');

    if (!modal || !form) return;

    let currentCategoryId = null;
    let currentCategoryName = null;

    // Character count
    if (motivationInput) {
        motivationInput.addEventListener('input', (e) => {
            if (charCount) charCount.textContent = e.target.value.length;
        });
    }

    // Form submission
    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const motivation = document.getElementById('motivationInput')?.value;
        const manifesto = document.getElementById('manifestoInput')?.value;
        const eligibility = document.getElementById('eligibilityCheckbox')?.checked;

        if (!currentCategoryId || !motivation || !eligibility) {
            showNotification('Please fill all required fields.', 'error');
            return;
        }

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Submitting...';

        try {
            const response = await apiRequest('/applications', {
                method: 'POST',
                body: JSON.stringify({
                    category_id: currentCategoryId,
                    statement: motivation,
                    manifesto_url: manifesto || null,
                }),
            });

            showNotification(response.message || 'Application submitted successfully!', 'success');

            // Close modal and refresh data
            closeModal();
            initializeVoterRegistration();

        } catch (error) {
            const errorMsg = error.message || error.data?.message || 'Failed to submit application';
            showNotification(errorMsg, 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    });

    // Close modal functionality
    function closeModal() {
        modal.classList.add('hidden');
        form.reset();
        if (charCount) charCount.textContent = '0';
        currentCategoryId = null;
        currentCategoryName = null;
    }

    // Close button listeners
    document.querySelectorAll('.closeModalBtn').forEach(btn => {
        btn.addEventListener('click', closeModal);
    });

    // Backdrop close
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });

    // EXPOSE GLOBAL FUNCTION FOR APPLY BUTTONS
    window.openApplicationModal = function(categoryId, categoryName, deadline) {
        currentCategoryId = categoryId;
        currentCategoryName = categoryName;
        document.getElementById('modalTitle').textContent = `Apply for ${categoryName}`;
        document.getElementById('modalDeadline').textContent = deadline;
        form.reset();
        if (charCount) charCount.textContent = '0';
        modal.classList.remove('hidden');
    };
}

// ========== BUTTON LISTENERS ==========

function attachApplyButtonListeners() {
    document.querySelectorAll('.apply-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.disabled) return;

            const categoryId = btn.dataset.categoryId;
            const categoryName = btn.dataset.categoryName;
            const deadline = btn.dataset.categoryDeadline;

            window.openApplicationModal(categoryId, categoryName, deadline);
        });
    });
}

function attachWithdrawListeners() {
    document.querySelectorAll('.withdraw-btn').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            e.preventDefault();

            const appId = btn.dataset.appId;

            if (!confirm('Are you sure you want to withdraw this application?')) {
                return;
            }

            // Show loading
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = '⏳ Withdrawing...';

            try {
                const response = await apiRequest(`/applications/${appId}`, {
                    method: 'DELETE',
                });

                showNotification(response.message || 'Application withdrawn successfully.', 'success');

                // Remove from DOM
                const card = document.getElementById(`app-${appId}`);
                if (card) card.remove();

                // Refresh all data
                initializeVoterRegistration();

            } catch (error) {
                showNotification(error.message || 'Failed to withdraw application.', 'error');
                btn.disabled = false;
                btn.textContent = originalText;
            }
        });
    });
}

// ========== PAGE INITIALIZATION ==========

document.addEventListener('DOMContentLoaded', () => {
    console.log('Voter Registration page loaded');
    setupModalHandling();

    // Listen for page switch events
    const observer = new MutationObserver(() => {
        const registerPage = document.getElementById('page-register');
        if (registerPage && registerPage.classList.contains('active-page')) {
            initializeVoterRegistration();
        }
    });

    const mainContent = document.querySelector('main');
    if (mainContent) {
        observer.observe(mainContent, { attributes: true, subtree: true, attributeFilter: ['class'] });
    }

    // Initial load if page is already active
    const registerPage = document.getElementById('page-register');
    if (registerPage && registerPage.classList.contains('active-page')) {
        initializeVoterRegistration();
    }
});
