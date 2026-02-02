/**
 * Main Application Bootstrap & Event Wiring
 * Orchestrates the entire application flow
 */

const App = {
    // Store modal context
    currentInternId: null,
    currentTaskId: null,
    currentUserRole: null,
    currentUsername: null,

    /**
     * Initialize the application
     */
    init() {
        // Check if user is logged in
        const userStr = localStorage.getItem('currentUser');
        const userRole = localStorage.getItem('userRole');
        
        if (userStr && userRole) {
            this.currentUsername = userStr;
            this.currentUserRole = userRole;
            this.showMainApp(userStr, userRole);
        } else {
            this.showAuthScreen();
        }

        this.setupEventListeners();
    },

    /**
     * Setup all event listeners
     */
    setupEventListeners() {
        // Authentication
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.addEventListener('submit', (e) => this.handleLogin(e));
        }
        
        const logoutBtn = document.getElementById('logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => this.handleLogout());
        }

        // Navigation
        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const view = e.currentTarget.dataset.view;
                if (view) {
                    Renderer.switchView(view);
                }
            });
        });

        // Intern Management
        const internForm = document.getElementById('intern-form');
        if (internForm) {
            internForm.addEventListener('submit', (e) => this.handleInternSubmit(e));
        }
        
        const filterStatus = document.getElementById('filter-status');
        if (filterStatus) {
            filterStatus.addEventListener('change', () => applyInternFilters());
        }
        
        const filterSkills = document.getElementById('filter-skills');
        if (filterSkills) {
            filterSkills.addEventListener('input', () => applyInternFilters());
        }

        // Task Management
        const taskForm = document.getElementById('task-form');
        if (taskForm) {
            taskForm.addEventListener('submit', (e) => this.handleTaskSubmit(e));
        }
        
        const filterLogAction = document.getElementById('filter-log-action');
        if (filterLogAction) {
            filterLogAction.addEventListener('change', () => applyLogFilters());
        }

        // Task Assignment
        const assignTaskSelect = document.getElementById('assign-task-select');
        if (assignTaskSelect) {
            assignTaskSelect.addEventListener('change', () => this.updateAssignmentHint());
        }
    },

    /**
     * Handle login
     */
    async handleLogin(e) {
        e.preventDefault();

        const username = document.getElementById('username').value.trim();
        const password = document.getElementById('password').value;
        const errorEl = document.getElementById('auth-error');

        // Client-side validation
        const validation = Validators.validateLogin(username, password);
        if (!validation.isValid) {
            errorEl.textContent = validation.errors[0];
            errorEl.style.display = 'block';
            return;
        }

        // Show loading
        Renderer.showLoading(true);

        try {
            // Async login simulation
            const response = await FakeServer.login(username, password);
            
            // Store user info
            localStorage.setItem('currentUser', response.user.username);
            localStorage.setItem('userRole', response.user.role);
            localStorage.setItem('userName', response.user.name);
            
            this.currentUsername = response.user.username;
            this.currentUserRole = response.user.role;
            
            errorEl.style.display = 'none';
            document.getElementById('login-form').reset();
            this.showMainApp(response.user.username, response.user.role, response.user.name);
        } catch (error) {
            errorEl.textContent = error.message;
            errorEl.style.display = 'block';
        } finally {
            Renderer.showLoading(false);
        }
    },

    /**
     * Handle logout
     */
    handleLogout() {
        localStorage.removeItem('currentUser');
        localStorage.removeItem('userRole');
        localStorage.removeItem('userName');
        this.currentUsername = null;
        this.currentUserRole = null;
        const loginForm = document.getElementById('login-form');
        if (loginForm) {
            loginForm.reset();
        }
        this.showAuthScreen();
    },

    /**
     * Show main application
     */
    showMainApp(username, role, fullName) {
        document.getElementById('auth-section').style.display = 'none';
        document.getElementById('main-app').style.display = 'flex';
        
        const roleText = role === 'admin' ? '👨‍💼 Administrator' : '👤 User';
        const displayText = `${roleText} - ${username}`;
        const userDisplay = document.getElementById('user-display');
        if (userDisplay) {
            userDisplay.textContent = displayText;
        }

        // Initialize view
        Renderer.switchView('dashboard');
    },

    /**
     * Show authentication screen
     */
    showAuthScreen() {
        document.getElementById('auth-section').style.display = 'flex';
        document.getElementById('main-app').style.display = 'none';
    },

    /**
     * Handle intern form submission
     */
    async handleInternSubmit(e) {
        e.preventDefault();

        // Check if user is admin
        if (this.currentUserRole !== 'admin') {
            Renderer.showError('❌ Only administrators can create interns', 'error-text');
            document.getElementById('error-banner').style.display = 'block';
            return;
        }

        const formData = {
            name: document.getElementById('intern-name').value.trim(),
            email: document.getElementById('intern-email').value.trim(),
            department: document.getElementById('intern-department').value,
            skills: document.getElementById('intern-skills').value.trim()
        };

        const errorEl = document.getElementById('intern-form-error');
        errorEl.style.display = 'none';

        Renderer.showLoading(true);

        try {
            const intern = await FakeServer.createIntern(formData);
            Renderer.onInternCreated(intern);
            Renderer.showError(`✓ Intern ${intern.id} created successfully`, 'error-text');
            document.getElementById('error-banner').style.display = 'block';
            e.target.reset();
        } catch (error) {
            errorEl.textContent = error.message;
            errorEl.style.display = 'block';
        } finally {
            Renderer.showLoading(false);
        }
    },

    /**
     * Handle task form submission
     */
    async handleTaskSubmit(e) {
        e.preventDefault();

        // Check if user is admin
        if (this.currentUserRole !== 'admin') {
            Renderer.showError('❌ Only administrators can create tasks', 'error-text');
            document.getElementById('error-banner').style.display = 'block';
            return;
        }

        const formData = {
            title: document.getElementById('task-title').value.trim(),
            description: document.getElementById('task-description').value.trim(),
            requiredSkills: document.getElementById('task-skills').value.trim(),
            hours: document.getElementById('task-hours').value,
            priority: document.getElementById('task-priority').value,
            dependencies: document.getElementById('task-dependencies').value.trim()
        };

        const errorEl = document.getElementById('task-form-error');
        errorEl.style.display = 'none';

        Renderer.showLoading(true);

        try {
            const task = await FakeServer.createTask(formData);
            Renderer.onTaskCreated(task);
            Renderer.showError(`✓ Task ${task.id} created successfully`, 'error-text');
            document.getElementById('error-banner').style.display = 'block';
            e.target.reset();
        } catch (error) {
            errorEl.textContent = error.message;
            errorEl.style.display = 'block';
        } finally {
            Renderer.showLoading(false);
        }
    }
};

/**
 * Global helper functions for inline event handlers
 */

function applyInternFilters() {
    const status = document.getElementById('filter-status').value;
    const skills = document.getElementById('filter-skills').value.toLowerCase().trim();

    let filtered = AppState.getAllInterns();

    // Filter by status if selected
    if (status && status.trim()) {
        filtered = filtered.filter(i => i.status === status);
    }

    // Filter by skills if entered
    if (skills) {
        filtered = filtered.filter(i => {
            if (!i.skills || i.skills.length === 0) return false;
            return i.skills.some(s => s.toLowerCase().includes(skills));
        });
    }

    Renderer.renderInternsList(filtered);
    console.log(`Filter applied: status="${status}", skills="${skills}", found ${filtered.length} interns`);
}

function clearInternFilters() {
    document.getElementById('filter-status').value = '';
    document.getElementById('filter-skills').value = '';
    Renderer.renderInternsList();
    console.log('Filters cleared, showing all interns');
}

function applyLogFilters() {
    const action = document.getElementById('filter-log-action').value;

    let filtered = AppState.getAllLogs();

    if (action) {
        filtered = filtered.filter(log => log.action === action);
    }

    Renderer.renderLogs(filtered);
}

function clearError() {
    document.getElementById('error-banner').style.display = 'none';
}

function openStatusModal(internId) {
    const intern = AppState.getInternById(internId);
    if (!intern) return;

    App.currentInternId = internId;
    const select = document.getElementById('modal-status-select');

    // Set current status
    select.value = intern.status;

    // Filter available transitions
    const validTransitions = {
        'ONBOARDING': ['ACTIVE'],
        'ACTIVE': ['EXITED'],
        'EXITED': []
    };

    // Update select options to only show valid transitions
    const options = select.querySelectorAll('option');
    options.forEach(opt => {
        const status = opt.value;
        const isValid = validTransitions[intern.status].includes(status) || status === intern.status;
        opt.disabled = !isValid;
    });

    document.getElementById('modal-error').style.display = 'none';
    document.getElementById('status-modal').style.display = 'flex';
}

function closeStatusModal() {
    document.getElementById('status-modal').style.display = 'none';
    App.currentInternId = null;
}

function confirmStatusUpdate() {
    const newStatus = document.getElementById('modal-status-select').value;
    const errorEl = document.getElementById('modal-error');
    errorEl.style.display = 'none';

    Renderer.showLoading(true);

    FakeServer.updateInternStatus(App.currentInternId, newStatus)
        .then(result => {
            Renderer.onStatusChanged();
            closeStatusModal();
            Renderer.showError(`✓ Status updated successfully`, 'error-text');
            document.getElementById('error-banner').style.display = 'block';
        })
        .catch(error => {
            errorEl.textContent = error.message;
            errorEl.style.display = 'block';
        })
        .finally(() => {
            Renderer.showLoading(false);
        });
}

function openTaskStatusModal(taskId) {
    const task = AppState.getTaskById(taskId);
    if (!task) return;

    App.currentTaskId = taskId;
    const select = document.getElementById('modal-task-status-select');
    const messageEl = document.getElementById('task-modal-message');

    // Set current status
    select.value = task.status;

    // Check if can mark as done
    const blockers = RulesEngine.getTaskBlockers(taskId);
    if (blockers.length > 0) {
        messageEl.style.display = 'block';
        messageEl.textContent = `⚠️ ${blockers.length} incomplete dependencies blocking completion`;
        select.disabled = true;
    } else {
        messageEl.style.display = 'none';
        select.disabled = false;
    }

    document.getElementById('task-modal-error').style.display = 'none';
    document.getElementById('task-status-modal').style.display = 'flex';
}

function closeTaskStatusModal() {
    document.getElementById('task-status-modal').style.display = 'none';
    App.currentTaskId = null;
}

function confirmTaskStatusUpdate() {
    const newStatus = document.getElementById('modal-task-status-select').value;
    const errorEl = document.getElementById('task-modal-error');
    errorEl.style.display = 'none';

    Renderer.showLoading(true);

    FakeServer.updateTaskStatus(App.currentTaskId, newStatus)
        .then(result => {
            Renderer.onStatusChanged();
            closeTaskStatusModal();
            Renderer.showError(`✓ Task status updated successfully`, 'error-text');
            document.getElementById('error-banner').style.display = 'block';
        })
        .catch(error => {
            errorEl.textContent = error.message;
            errorEl.style.display = 'block';
        })
        .finally(() => {
            Renderer.showLoading(false);
        });
}

function assignTask() {
    const taskId = document.getElementById('assign-task-select').value;
    const internId = document.getElementById('assign-intern-select').value;
    const errorEl = document.getElementById('assign-error');

    if (!taskId || !internId) {
        errorEl.textContent = 'Please select both task and intern';
        errorEl.style.display = 'block';
        return;
    }

    errorEl.style.display = 'none';
    Renderer.showLoading(true);

    FakeServer.assignTaskToIntern(taskId, internId)
        .then(result => {
            Renderer.onTaskAssigned(taskId, internId);
            document.getElementById('assign-task-select').value = '';
            document.getElementById('assign-intern-select').value = '';
            Renderer.showError(`✓ Task assigned successfully`, 'error-text');
            document.getElementById('error-banner').style.display = 'block';
        })
        .catch(error => {
            errorEl.textContent = error.message;
            errorEl.style.display = 'block';
        })
        .finally(() => {
            Renderer.showLoading(false);
        });
}

function updateAssignmentHint() {
    const taskId = document.getElementById('assign-task-select').value;
    const task = AppState.getTaskById(taskId);
    if (task) {
        // Filter interns by skills
        const activeInterns = AppState.getAllInterns()
            .filter(i => i.status === 'ACTIVE')
            .filter(i => RulesEngine.internHasRequiredSkills(i, task.requiredSkills));
        
        const internSelect = document.getElementById('assign-intern-select');
        const currentValue = internSelect.value;
        
        internSelect.innerHTML = '<option value="">Choose an intern...</option>';
        activeInterns.forEach(intern => {
            const option = document.createElement('option');
            option.value = intern.id;
            option.textContent = `${intern.id} - ${intern.name}`;
            internSelect.appendChild(option);
        });
        
        internSelect.value = currentValue;
    }
}

function deleteIntern(internId) {
    if (!confirm('Are you sure? This will remove the intern from the system.')) {
        return;
    }

    const internIdx = AppState.interns.findIndex(i => i.id === internId);
    if (internIdx >= 0) {
        const intern = AppState.interns[internIdx];
        
        // Unassign all tasks
        intern.tasksAssigned.forEach(taskId => {
            AppState.unassignTask(taskId, internId);
        });

        // Remove intern
        AppState.interns.splice(internIdx, 1);
        AppState.save();
        AppState.addLog('DELETE_INTERN', `Deleted intern ${internId}`, 'info');

        Renderer.renderInternsList();
        Renderer.updateAssignmentSelects();
        Renderer.renderStats();
        Renderer.showError(`✓ Intern deleted`, 'error-text');
        document.getElementById('error-banner').style.display = 'block';
    }
}

function deleteTask(taskId) {
    if (!confirm('Are you sure? This will remove the task from the system.')) {
        return;
    }

    const taskIdx = AppState.tasks.findIndex(t => t.id === taskId);
    if (taskIdx >= 0) {
        const task = AppState.tasks[taskIdx];
        
        // Unassign if assigned
        if (task.assignedTo) {
            AppState.unassignTask(taskId, task.assignedTo);
        }

        // Remove task
        AppState.tasks.splice(taskIdx, 1);
        AppState.save();
        AppState.addLog('DELETE_TASK', `Deleted task ${taskId}`, 'info');

        Renderer.renderTasksList();
        Renderer.updateAssignmentSelects();
        Renderer.renderStats();
        Renderer.showError(`✓ Task deleted`, 'error-text');
        document.getElementById('error-banner').style.display = 'block';
    }
}

/**
 * Bootstrap application when DOM is ready
 */
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        App.init();
    });
} else {
    App.init();
}
