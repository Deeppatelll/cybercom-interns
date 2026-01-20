/**
 * Business Rules Engine
 * Handles complex business logic and constraints
 */

const RulesEngine = {
    /**
     * Check if intern has required skills
     */
    internHasRequiredSkills(intern, requiredSkills) {
        const internSkillsLower = intern.skills.map(s => s.toLowerCase());
        return requiredSkills.every(req => 
            internSkillsLower.includes(req.toLowerCase())
        );
    },

    /**
     * Check valid status transition for interns
     * ONBOARDING -> ACTIVE -> EXITED (one-way)
     */
    isValidStatusTransition(currentStatus, newStatus) {
        const validTransitions = {
            'ONBOARDING': ['ACTIVE'],
            'ACTIVE': ['EXITED'],
            'EXITED': [] // No way back
        };

        if (!validTransitions[currentStatus]) {
            return false;
        }

        return validTransitions[currentStatus].includes(newStatus);
    },

    /**
     * Detect circular dependencies
     */
    hasCircularDependency(dependencies, taskId) {
        const visited = new Set();
        const recursionStack = new Set();

        const dfs = (task) => {
            if (!task) return false;
            
            visited.add(task.id);
            recursionStack.add(task.id);

            for (let depId of (task.dependencies || [])) {
                if (!visited.has(depId)) {
                    const depTask = AppState.getTaskById(depId);
                    if (dfs(depTask)) return true;
                } else if (recursionStack.has(depId)) {
                    return true;
                }
            }

            recursionStack.delete(task.id);
            return false;
        };

        const startTask = AppState.getTaskById(taskId);
        return dfs(startTask);
    },

    /**
     * Auto-update tasks when their dependencies are resolved
     */
    autoUpdateDependentTasks(completedTaskId) {
        const allTasks = AppState.getAllTasks();
        
        for (let task of allTasks) {
            // Skip if already done or not in progress
            if (task.status === 'DONE' || task.status === 'BLOCKED') {
                continue;
            }

            // Check if this task depends on the completed task
            if (!task.dependencies.includes(completedTaskId)) {
                continue;
            }

            // Check if all dependencies are now done
            const allDependenciesDone = task.dependencies.every(depId => {
                const depTask = AppState.getTaskById(depId);
                return depTask && depTask.status === 'DONE';
            });

            // Auto-update status to TODO if was blocked and dependencies resolved
            if (allDependenciesDone && task.status === 'BLOCKED') {
                AppState.updateTaskStatus(task.id, 'TODO');
                AppState.addLog('AUTO_UPDATE', `Task ${task.id} unblocked (dependencies resolved)`, 'success');
            }
        }
    },

    /**
     * Calculate total estimated hours for intern
     */
    calculateTotalHoursForIntern(internId) {
        const tasks = AppState.getTasksForIntern(internId);
        return tasks.reduce((total, task) => total + task.estimatedHours, 0);
    },

    /**
     * Get task dependency chain (for display)
     */
    getDependencyChain(taskId) {
        const chain = [];
        const visited = new Set();

        const traverse = (id) => {
            if (visited.has(id)) return;
            visited.add(id);

            const task = AppState.getTaskById(id);
            if (!task) return;

            chain.push({
                id: task.id,
                title: task.title,
                status: task.status
            });

            for (let depId of task.dependencies) {
                traverse(depId);
            }
        };

        traverse(taskId);
        return chain;
    },

    /**
     * Get tasks that depend on given task
     */
    getTasksThatDependOn(taskId) {
        const allTasks = AppState.getAllTasks();
        return allTasks.filter(task => task.dependencies.includes(taskId));
    },

    /**
     * Validate task can be marked as DONE
     */
    canMarkTaskAsDone(taskId) {
        const task = AppState.getTaskById(taskId);
        if (!task) return false;

        // Check all dependencies are done
        const incompleteDeps = task.dependencies.filter(depId => {
            const depTask = AppState.getTaskById(depId);
            return depTask && depTask.status !== 'DONE';
        });

        return incompleteDeps.length === 0;
    },

    /**
     * Get blockers for task
     */
    getTaskBlockers(taskId) {
        const task = AppState.getTaskById(taskId);
        if (!task || task.dependencies.length === 0) return [];

        return task.dependencies
            .map(depId => AppState.getTaskById(depId))
            .filter(t => t && t.status !== 'DONE');
    },

    /**
     * Check if intern can be exited (no active tasks)
     */
    canExitIntern(internId) {
        const tasks = AppState.getTasksForIntern(internId);
        const activeTasks = tasks.filter(t => t.status !== 'DONE' && t.status !== 'BLOCKED');
        return activeTasks.length === 0;
    },

    /**
     * Get skills mismatch for task assignment
     */
    getSkillsMismatch(internId, taskId) {
        const intern = AppState.getInternById(internId);
        const task = AppState.getTaskById(taskId);

        if (!intern || !task) return [];

        const internSkillsLower = intern.skills.map(s => s.toLowerCase());
        return task.requiredSkills.filter(req => 
            !internSkillsLower.includes(req.toLowerCase())
        );
    }
};
