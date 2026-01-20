# Frontend-Only Intern Operations System

## Overview
A single-page application (SPA) that manages intern lifecycle, task assignment, and dependencies **entirely in JavaScript** without any backend API. All data persists using localStorage, and all async behavior is simulated using promises.

## Features

### 1. **Authentication** (Bonus Feature)
- Login/Logout using localStorage
- Credentials: `admin/admin123` or `manager/manager123`
- Session persistence across page reloads

### 2. **Intern Management**
- **Create Interns** with auto-generated IDs (YYYY + sequence, e.g., I20260001)
- **Email Uniqueness**: Async validation simulated with 500ms delay
- **Skill Tracking**: Comma-separated skills for task matching
- **Lifecycle States**: ONBOARDING → ACTIVE → EXITED
- **Status Transitions**: Only valid paths allowed (one-way progression)
- **Task Assignments**: View assigned tasks and total hours per intern

### 3. **Task Management**
- **Create Tasks** with auto-generated IDs (T20260001, T20260002, etc.)
- **Priority Levels**: LOW, MEDIUM, HIGH
- **Required Skills**: Tasks matched to intern skills
- **Dependency Resolution**: 
  - Tasks can depend on other tasks
  - Cannot mark DONE if dependencies unresolved
  - Auto-status update when blockers cleared
  - Circular dependency detection
- **Task Status**: TODO → IN_PROGRESS → DONE or BLOCKED
- **Estimated Hours**: Dynamic recalculation

### 4. **Task Assignment**
- Only assign to ACTIVE interns
- Skill-based matching
- Prevent duplicate assignments
- Auto-filter intern list by required skills

### 5. **Data Consistency**
- Single global state object (AppState)
- No duplicated data
- Real-time UI sync across sections
- localStorage persistence

### 6. **Activity Logs**
- Timestamped action history
- Filter by action type
- Success/error indicators
- Keeps last 500 entries

## Architecture

### Module Separation

```
js/
├── state.js          # Central state management
├── validators.js     # Form and business rule validation
├── fake-server.js    # Async simulation (500ms delays)
├── rules-engine.js   # Business logic & constraints
├── renderer.js       # DOM manipulation only
└── app.js           # Bootstrap & event wiring
```

### State Management
- **Single Source of Truth**: `AppState` object
- **No Derived State**: All derived values calculated on-render
- **Methods Only**: State changes only through AppState methods
- **Persistence**: Auto-saved to localStorage

### Async Simulation
All async operations (500ms delay):
- Login validation
- Email uniqueness check
- Intern creation
- Task creation
- Status updates
- Task assignment

### Rendering Philosophy
- **Renderer Module**: Only handles DOM updates
- **No Logic in Handlers**: Event handlers delegate to Renderer
- **Re-render on Change**: Full or partial re-renders as needed
- **Status Badges**: Inline rendering with CSS classes

## User Flows

### Create Intern
1. Enter name, email, department, skills
2. Client-side validation
3. Async email check (simulated)
4. Add to state if valid
5. Re-render tables
6. Log action

### Assign Task
1. Select unassigned task
2. Intern list filters by required skills
3. Validate intern is ACTIVE
4. Validate no skill gaps
5. Assign and re-render
6. Log action

### Update Intern Status
1. Click Status button on intern
2. Modal shows valid transitions only
3. ONBOARDING → ACTIVE → EXITED (one-way)
4. EXITED has no valid transitions
5. Update state and re-render
6. Log action

### Mark Task DONE
1. Click Status button on task
2. Modal shows blockers if any
3. Cannot mark DONE if dependencies unresolved
4. On completion, check dependent tasks
5. Auto-unblock tasks with all dependencies met
6. Log action

## Business Rules

### Intern Rules
- **ID Generation**: `I` + year + 3-digit sequence
- **Email**: Must be unique (async check)
- **Skills**: At least one required
- **Status**: ONBOARDING → ACTIVE → EXITED (irreversible)
- **Task Assignment**: Only when ACTIVE

### Task Rules
- **ID Generation**: `T` + year + 3-digit sequence
- **Status**: TODO → IN_PROGRESS → DONE or BLOCKED
- **Dependencies**: Must exist and be valid
- **Circular Check**: Detected and blocked
- **DONE Condition**: All dependencies must be DONE
- **Auto-Update**: Tasks unblock when deps resolve
- **Required Skills**: Must match assigned intern

### Assignment Rules
- Intern must be ACTIVE
- Intern must have all required skills
- No duplicate assignments
- One intern per task
- Multiple tasks per intern allowed

## Technical Implementation

### localStorage Persistence
```javascript
// Auto-saved whenever state changes
{
    "interns": [...],
    "tasks": [...],
    "logs": [...],
    "internSequence": 5,
    "taskSequence": 10
}
```

### Async Simulation
```javascript
// All FakeServer methods return Promises with 500ms delay
async createIntern(data) {
    return new Promise((resolve, reject) => {
        setTimeout(() => {
            // validation
            // state update
            resolve(intern);
        }, 500);
    });
}
```

### Dependency Resolution
```javascript
// Circular detection using DFS
hasCircularDependency(dependencies, taskId)

// Auto-status update
autoUpdateDependentTasks(completedTaskId)

// Get blockers
getTaskBlockers(taskId)
```

## UI Sections

### Dashboard
- Total interns, active interns
- Total tasks, pending tasks
- Real-time stats

### Manage Interns
- Create form with validation
- Filter by status and skills
- Table with actions (Status, Delete)
- Task assignment summary

### Manage Tasks
- Create form with dependency support
- Assign section with skill filtering
- Table with status and blockers
- Dependency chain display

### Activity Logs
- Timestamped entries
- Filter by action
- 500 entry history limit

## Validation Examples

### Form Validation
```javascript
// Name: min 2 chars
// Email: valid format + unique
// Skills: at least one
// Hours: positive integer
// Dependencies: valid task IDs
```

### Business Validation
```javascript
// Status transition: ONBOARDING→ACTIVE→EXITED only
// Task assignment: intern must be ACTIVE
// Task completion: dependencies must be DONE
// Circular dependency: blocked
```

## Performance Considerations

- **No Framework Overhead**: Pure JavaScript
- **Efficient Rendering**: Only update changed sections
- **Minimal DOM Queries**: Cached references
- **Debounced Filters**: Real-time filtering
- **localStorage**: No server calls

## How to Use

### 1. Open Application
Open `index.html` in a modern browser

### 2. Login
Use credentials:
- Username: `admin` / Password: `admin123`
- Username: `manager` / Password: `manager123`

### 3. Create Interns
- Go to "Manage Interns" tab
- Fill in details with skills (comma-separated)
- System auto-generates ID

### 4. Create Tasks
- Go to "Manage Tasks" tab
- Specify title, hours, required skills
- Optionally add task dependencies

### 5. Update Statuses
- Intern progress: ONBOARDING → ACTIVE → EXITED
- Task progress: TODO → IN_PROGRESS → DONE
- Cannot mark DONE with unresolved dependencies

### 6. Assign Tasks
- Select task and intern in "Task Assignment"
- Intern must be ACTIVE
- Intern must have required skills
- Task auto-filters compatible interns

### 7. Monitor Activity
- View timestamped logs
- Filter by action type
- Track all state changes

## Browser Compatibility

- Modern browsers (Chrome, Firefox, Safari, Edge)
- Requires localStorage support
- Requires Promise support
- ES6 JavaScript

## Data Persistence

- All data stored in browser's localStorage
- Persists across page reloads
- Clear by deleting browser data
- Each user/browser has isolated state

## No External Dependencies

- **Zero npm packages**
- **No frameworks** (React, Vue, Angular)
- **No build tools** (Webpack, Vite)
- **Pure vanilla JavaScript**
- **Standard CSS**

## Testing the System

### Scenario 1: Create & Assign
1. Create Intern A with "JavaScript" skill
2. Create Task A requiring "JavaScript"
3. Promote Intern A to ACTIVE
4. Assign Task A to Intern A
5. See task count update in Intern table

### Scenario 2: Dependency Blocking
1. Create Task A
2. Create Task B (depends on Task A)
3. Try to mark Task B as DONE → Fails (blocked)
4. Mark Task A as DONE
5. Task B status auto-updates to TODO (unblocked)

### Scenario 3: Status Transitions
1. Create new intern (status: ONBOARDING)
2. Try to assign task → Fails (must be ACTIVE)
3. Change to ACTIVE → Can now assign
4. Change to EXITED → No reverse transition allowed

### Scenario 4: Skill Matching
1. Intern with skills: JavaScript, React
2. Task requiring: Python, Django
3. Try to assign → Fails (skill gap)
4. Create another intern with matching skills
5. Assign successfully

## Features Summary

✅ Single-page behavior (no reload)
✅ Central state management
✅ Auto-generated IDs (year-based)
✅ Async email validation (simulated)
✅ Skill-based task assignment
✅ Task dependency resolution
✅ Circular dependency detection
✅ Status transition rules
✅ Role-based views (Manager view)
✅ Activity logging (timestamped)
✅ Data persistence (localStorage)
✅ Login/Logout (localStorage session)
✅ Filter & search capabilities
✅ Error handling & validation
✅ Zero external dependencies

---

**Built with:** Vanilla JavaScript, CSS3, HTML5
**No APIs, No Backend, No Database** - Everything in JavaScript!
