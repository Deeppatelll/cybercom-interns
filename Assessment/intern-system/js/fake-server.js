/**
 * Fake Server - Simulates async backend behavior
 * Uses setTimeout to simulate network delays
 */

const FakeServer = {
    // Simulated delay in ms
    DELAY: 500,
    
    // Simulated database of registered users with roles
    USERS: [
        { username: 'admin', password: 'admin123', role: 'admin', name: 'Administrator' },
        { username: 'user', password: 'user123', role: 'user', name: 'Regular User' }
    ],

    /**
     * Simulate login API call
     */
    async login(username, password) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const user = this.USERS.find(u => u.username === username && u.password === password);
                if (user) {
                    resolve({
                        success: true,
                        user: { 
                            username: user.username, 
                            role: user.role,
                            name: user.name
                        }
                    });
                } else {
                    reject(new Error('Invalid username or password'));
                }
            }, this.DELAY);
        });
    },

    /**
     * Simulate email uniqueness check
     */
    async checkEmailUniqueness(email) {
        return new Promise((resolve) => {
            setTimeout(() => {
                const exists = AppState.isEmailUsed(email);
                resolve({ available: !exists });
            }, this.DELAY);
        });
    },

    /**
     * Simulate creating intern
     */
    async createIntern(internData) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // Validation
                const validation = Validators.validateInternForm(internData);
                if (!validation.isValid) {
                    reject(new Error(validation.errors.join(', ')));
                    return;
                }

                // Email uniqueness
                if (AppState.isEmailUsed(internData.email)) {
                    reject(new Error('Email already in use'));
                    return;
                }

                // Create
                const intern = AppState.addIntern({
                    name: internData.name,
                    email: internData.email,
                    department: internData.department,
                    skills: Validators.parseSkills(internData.skills)
                });

                resolve(intern);
            }, this.DELAY);
        });
    },

    /**
     * Simulate creating task
     */
    async createTask(taskData) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                // Validation
                const validation = Validators.validateTaskForm(taskData);
                if (!validation.isValid) {
                    reject(new Error(validation.errors.join(', ')));
                    return;
                }

                // Check dependencies exist
                const deps = Validators.parseDependencies(taskData.dependencies);
                for (let depId of deps) {
                    if (!AppState.getTaskById(depId)) {
                        reject(new Error(`Dependency ${depId} does not exist`));
                        return;
                    }
                }

                // Check for circular dependencies
                if (RulesEngine.hasCircularDependency(deps, taskData.taskId)) {
                    reject(new Error('Circular dependency detected'));
                    return;
                }

                // Create task
                const task = AppState.addTask({
                    title: taskData.title,
                    description: taskData.description,
                    requiredSkills: Validators.parseSkills(taskData.requiredSkills),
                    priority: taskData.priority,
                    estimatedHours: parseInt(taskData.hours),
                    dependencies: deps
                });

                resolve(task);
            }, this.DELAY);
        });
    },

    /**
     * Simulate assigning task to intern
     */
    async assignTaskToIntern(taskId, internId) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const task = AppState.getTaskById(taskId);
                const intern = AppState.getInternById(internId);

                if (!task || !intern) {
                    reject(new Error('Task or Intern not found'));
                    return;
                }

                // Can only assign to ACTIVE interns
                if (intern.status !== 'ACTIVE') {
                    reject(new Error(`Cannot assign to ${intern.status} intern`));
                    return;
                }

                // Check skill match
                if (!RulesEngine.internHasRequiredSkills(intern, task.requiredSkills)) {
                    reject(new Error(`Intern does not have required skills: ${task.requiredSkills.join(', ')}`));
                    return;
                }

                // Prevent duplicate assignment
                if (task.assignedTo === internId) {
                    reject(new Error('Task already assigned to this intern'));
                    return;
                }

                if (task.assignedTo) {
                    reject(new Error('Task is already assigned to another intern'));
                    return;
                }

                // Assign
                AppState.assignTask(taskId, internId);
                resolve({ taskId, internId });
            }, this.DELAY);
        });
    },

    /**
     * Simulate updating intern status
     */
    async updateInternStatus(internId, newStatus) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const intern = AppState.getInternById(internId);
                if (!intern) {
                    reject(new Error('Intern not found'));
                    return;
                }

                // Check valid transition
                const validTransition = RulesEngine.isValidStatusTransition(intern.status, newStatus);
                if (!validTransition) {
                    reject(new Error(`Cannot transition from ${intern.status} to ${newStatus}`));
                    return;
                }

                AppState.updateInternStatus(internId, newStatus);
                resolve({ internId, newStatus });
            }, this.DELAY);
        });
    },

    /**
     * Simulate updating task status
     */
    async updateTaskStatus(taskId, newStatus) {
        return new Promise((resolve, reject) => {
            setTimeout(() => {
                const task = AppState.getTaskById(taskId);
                if (!task) {
                    reject(new Error('Task not found'));
                    return;
                }

                // Cannot move to DONE if has incomplete dependencies
                if (newStatus === 'DONE' && task.dependencies.length > 0) {
                    const incompleteDeps = task.dependencies.filter(depId => {
                        const depTask = AppState.getTaskById(depId);
                        return depTask && depTask.status !== 'DONE';
                    });

                    if (incompleteDeps.length > 0) {
                        reject(new Error(`Cannot mark DONE. Incomplete dependencies: ${incompleteDeps.join(', ')}`));
                        return;
                    }
                }

                AppState.updateTaskStatus(taskId, newStatus);

                // Check if dependencies can now be auto-updated
                setTimeout(() => {
                    RulesEngine.autoUpdateDependentTasks(taskId);
                }, 100);

                resolve({ taskId, newStatus });
            }, this.DELAY);
        });
    }
};
