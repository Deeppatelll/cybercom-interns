/**
 * Renderer - Handles all DOM updates
 * This module contains all rendering logic
 */

const Renderer = {
    /**
     * Render dashboard stats
     */
    renderStats() {
        const stats = AppState.getStats();
        document.getElementById('stat-total-interns').textContent = stats.totalInterns;
        document.getElementById('stat-active-interns').textContent = stats.activeInterns;
        document.getElementById('stat-total-tasks').textContent = stats.totalTasks;
        document.getElementById('stat-pending-tasks').textContent = stats.pendingTasks;
    },

    /**
     * Render interns table
     */
    renderInternsList(interns = null) {
        const tbody = document.getElementById('interns-tbody');
        const internsList = interns || AppState.getAllInterns();

        tbody.innerHTML = '';

        if (internsList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align: center;">No interns found</td></tr>';
            return;
        }

        internsList.forEach(intern => {
            const row = document.createElement('tr');
            const tasksCount = intern.tasksAssigned.length;
            const totalHours = intern.tasksAssigned.reduce((sum, taskId) => {
                const task = AppState.getTaskById(taskId);
                return sum + (task ? task.estimatedHours : 0);
            }, 0);

            row.innerHTML = `
                <td><strong>${intern.id}</strong></td>
                <td>${this.escapeHtml(intern.name)}</td>
                <td>${this.escapeHtml(intern.email)}</td>
                <td>${this.escapeHtml(intern.department)}</td>
                <td>${this.renderSkillsList(intern.skills)}</td>
                <td>${this.renderStatusBadge(intern.status)}</td>
                <td>${tasksCount} (${totalHours}h)</td>
                <td>
                    <div class="admin-only table-actions">
                        <button class="btn btn-secondary" onclick="openStatusModal('${intern.id}')">Status</button>
                        <button class="btn btn-danger" onclick="deleteIntern('${intern.id}')">Delete</button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    },

    /**
     * Render tasks table
     */
    renderTasksList(tasks = null) {
        const tbody = document.getElementById('tasks-tbody');
        const tasksList = tasks || AppState.getAllTasks();

        tbody.innerHTML = '';

        if (tasksList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;">No tasks found</td></tr>';
            return;
        }

        tasksList.forEach(task => {
            const row = document.createElement('tr');
            const assignedIntern = task.assignedTo ? AppState.getInternById(task.assignedTo) : null;
            const blockers = RulesEngine.getTaskBlockers(task.id);
            const blockersText = blockers.length > 0 ? `(${blockers.length} blocker${blockers.length !== 1 ? 's' : ''})` : '';

            row.innerHTML = `
                <td><strong>${task.id}</strong></td>
                <td>${this.escapeHtml(task.title)}</td>
                <td>${this.renderPriorityBadge(task.priority)}</td>
                <td>${this.renderTaskStatusBadge(task.status)}</td>
                <td>${task.estimatedHours}h</td>
                <td>${this.renderSkillsList(task.requiredSkills)}</td>
                <td>${assignedIntern ? assignedIntern.name : '-'}</td>
                <td><small>${task.dependencies.join(', ') || '-'} ${blockersText}</small></td>
                <td>
                    <div class="admin-only table-actions">
                        <button class="btn btn-secondary" onclick="openTaskStatusModal('${task.id}')">Status</button>
                        <button class="btn btn-danger" onclick="deleteTask('${task.id}')">Delete</button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    },

    /**
     * Render activity logs
     */
    renderLogs(logs = null) {
        const tbody = document.getElementById('logs-tbody');
        const logsList = logs || AppState.getAllLogs();

        tbody.innerHTML = '';

        if (logsList.length === 0) {
            tbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No logs found</td></tr>';
            return;
        }

        // Show most recent first
        const sorted = [...logsList].reverse();

        sorted.forEach(log => {
            const row = document.createElement('tr');
            const date = new Date(log.timestamp);
            const timeStr = date.toLocaleString();

            row.innerHTML = `
                <td>${timeStr}</td>
                <td><strong>${log.action}</strong></td>
                <td>${this.escapeHtml(log.details)}</td>
                <td>${this.renderStatusBadge(log.status)}</td>
            `;
            tbody.appendChild(row);
        });
    },

    /**
     * Render status badge
     */
    renderStatusBadge(status) {
        const statusLower = status.toLowerCase().replace('_', '');
        return `<span class="status-badge status-${statusLower}">${status}</span>`;
    },

    /**
     * Render task status badge
     */
    renderTaskStatusBadge(status) {
        const statusLower = status.toLowerCase().replace('_', '');
        return `<span class="status-badge status-${statusLower}">${status}</span>`;
    },

    /**
     * Render priority badge
     */
    renderPriorityBadge(priority) {
        const priorityLower = priority.toLowerCase();
        return `<span class="priority-badge priority-${priorityLower}">${priority}</span>`;
    },

    /**
     * Render skills list
     */
    renderSkillsList(skills) {
        if (!skills || skills.length === 0) return '-';
        return skills.map(skill => `<span class="skill-tag">${this.escapeHtml(skill)}</span>`).join(' ');
    },

    /**
     * Update assignment dropdowns
     */
    updateAssignmentSelects() {
        const taskSelect = document.getElementById('assign-task-select');
        const internSelect = document.getElementById('assign-intern-select');

        // Update task select
        taskSelect.innerHTML = '<option value="">Choose a task...</option>';
        const unassignedTasks = AppState.getAllTasks().filter(t => !t.assignedTo);
        unassignedTasks.forEach(task => {
            const option = document.createElement('option');
            option.value = task.id;
            option.textContent = `${task.id} - ${task.title}`;
            taskSelect.appendChild(option);
        });

        // Update intern select
        internSelect.innerHTML = '<option value="">Choose an intern...</option>';
        const activeInterns = AppState.getAllInterns().filter(i => i.status === 'ACTIVE');
        activeInterns.forEach(intern => {
            const option = document.createElement('option');
            option.value = intern.id;
            option.textContent = `${intern.id} - ${intern.name}`;
            internSelect.appendChild(option);
        });
    },

    /**
     * Show error message
     */
    showError(message, elementId = 'error-banner') {
        const element = document.getElementById(elementId);
        if (!element) return;

        const textElement = element.querySelector('span') || element;
        textElement.textContent = message;
        element.style.display = 'block';

        // Auto-hide after 5 seconds
        setTimeout(() => {
            element.style.display = 'none';
        }, 5000);
    },

    /**
     * Show loading
     */
    showLoading(show = true) {
        const banner = document.getElementById('loading-banner');
        banner.style.display = show ? 'block' : 'none';
    },

    /**
     * HTML escape
     */
    escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    },

    /**
     * Switch view
     */
    switchView(viewName) {
        // Hide all views
        document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
        // Deactivate all nav buttons
        document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));

        // Show selected view
        const viewEl = document.getElementById(viewName);
        if (viewEl) {
            viewEl.classList.add('active');
        }

        // Activate nav button
        const navBtn = document.querySelector(`[data-view="${viewName}"]`);
        if (navBtn) {
            navBtn.classList.add('active');
        }

        AppState.currentView = viewName;

        // Render content based on view
        this.renderStats();
        if (viewName === 'interns') {
            this.renderInternsList();
            this.updateAssignmentSelects();
        } else if (viewName === 'tasks') {
            this.renderTasksList();
            this.updateAssignmentSelects();
        } else if (viewName === 'logs') {
            this.renderLogs();
        }

        // Apply role-based visibility AFTER rendering
        this.applyRoleBasedAccess();
    },

    /**
     * Apply role-based access control
     */
    applyRoleBasedAccess() {
        const userRole = localStorage.getItem('userRole');
        const isAdmin = userRole === 'admin';

        // Admin-only sections
        const adminOnlySections = document.querySelectorAll('.admin-only');
        adminOnlySections.forEach(section => {
            if (isAdmin) {
                section.classList.remove('hidden');
            } else {
                section.classList.add('hidden');
            }
        });
    },

    /**
     * Update UI on intern creation
     */
    onInternCreated(intern) {
        this.renderInternsList();
        this.updateAssignmentSelects();
        this.renderStats();
    },

    /**
     * Update UI on task creation
     */
    onTaskCreated(task) {
        this.renderTasksList();
        this.updateAssignmentSelects();
        this.renderStats();
    },

    /**
     * Update UI on assignment
     */
    onTaskAssigned(taskId, internId) {
        this.renderTasksList();
        this.renderInternsList();
        this.updateAssignmentSelects();
        this.renderStats();
    },

    /**
     * Update UI on status change
     */
    onStatusChanged() {
        this.renderInternsList();
        this.renderTasksList();
        this.updateAssignmentSelects();
        this.renderStats();
    }
};
