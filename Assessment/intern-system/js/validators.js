/**
 * Form and Data Validation
 */

const Validators = {
    /**
     * Validate email format
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    /**
     * Validate name (not empty, min 2 chars)
     */
    isValidName(name) {
        return name && name.trim().length >= 2;
    },

    /**
     * Validate skills (comma-separated, non-empty)
     */
    isValidSkills(skillsString) {
        if (!skillsString || !skillsString.trim()) return false;
        const skills = skillsString.split(',').map(s => s.trim());
        return skills.every(s => s.length > 0);
    },

    /**
     * Parse skills string into array
     */
    parseSkills(skillsString) {
        return skillsString
            .split(',')
            .map(s => s.trim())
            .filter(s => s.length > 0);
    },

    /**
     * Validate task title
     */
    isValidTaskTitle(title) {
        return title && title.trim().length >= 3;
    },

    /**
     * Validate estimated hours
     */
    isValidHours(hours) {
        const h = parseInt(hours);
        return !isNaN(h) && h > 0;
    },

    /**
     * Validate required skills
     */
    isValidRequiredSkills(skillsString) {
        return this.isValidSkills(skillsString);
    },

    /**
     * Validate task dependencies (format: T202X001, T202X002)
     */
    isValidDependencies(depsString) {
        if (!depsString || !depsString.trim()) return true; // Optional
        const deps = depsString.split(',').map(d => d.trim());
        return deps.every(d => /^T\d{4}\d{3}$/.test(d));
    },

    /**
     * Parse dependencies string into array
     */
    parseDependencies(depsString) {
        if (!depsString || !depsString.trim()) return [];
        return depsString
            .split(',')
            .map(d => d.trim())
            .filter(d => d.length > 0);
    },

    /**
     * Validate intern form
     */
    validateInternForm(formData) {
        const errors = [];

        if (!this.isValidName(formData.name)) {
            errors.push('Name must be at least 2 characters');
        }

        if (!this.isValidEmail(formData.email)) {
            errors.push('Invalid email format');
        }

        if (!this.isValidSkills(formData.skills)) {
            errors.push('Please enter at least one skill');
        }

        if (AppState.isEmailUsed(formData.email)) {
            errors.push('Email is already in use');
        }

        return {
            isValid: errors.length === 0,
            errors
        };
    },

    /**
     * Validate task form
     */
    validateTaskForm(formData) {
        const errors = [];

        if (!this.isValidTaskTitle(formData.title)) {
            errors.push('Title must be at least 3 characters');
        }

        if (!this.isValidHours(formData.hours)) {
            errors.push('Estimated hours must be a positive number');
        }

        if (!this.isValidRequiredSkills(formData.requiredSkills)) {
            errors.push('Please enter at least one required skill');
        }

        if (!this.isValidDependencies(formData.dependencies)) {
            errors.push('Invalid dependency format. Use comma-separated task IDs (e.g., T20260001, T20260002)');
        }

        return {
            isValid: errors.length === 0,
            errors
        };
    },

    /**
     * Validate login credentials
     */
    validateLogin(username, password) {
        const errors = [];

        if (!username || username.trim().length < 3) {
            errors.push('Username must be at least 3 characters');
        }

        if (!password || password.length < 4) {
            errors.push('Password must be at least 4 characters');
        }

        return {
            isValid: errors.length === 0,
            errors
        };
    }
};
