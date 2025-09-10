import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Drag and Drop functionality
document.addEventListener('alpine:init', () => {
    Alpine.data('boardKanban', () => ({
        draggedTask: null,
        draggedFromColumn: null,
        
        startDrag(taskId, columnId) {
            this.draggedTask = taskId;
            this.draggedFromColumn = columnId;
            
            // Add dragging class to the task
            const taskElement = document.querySelector(`[data-task-id="${taskId}"]`);
            if (taskElement) {
                taskElement.classList.add('dragging');
            }
        },
        
        endDrag() {
            this.draggedTask = null;
            this.draggedFromColumn = null;
            
            // Remove dragging class from all tasks
            document.querySelectorAll('.kanban-task').forEach(task => {
                task.classList.remove('dragging');
            });
            
            // Remove drop zone styling
            document.querySelectorAll('.kanban-column').forEach(column => {
                column.classList.remove('drop-zone');
            });
        },
        
        handleDrop(targetColumnId) {
            if (this.draggedTask && this.draggedFromColumn !== targetColumnId) {
                // Call Livewire method to move the task
                this.$wire.moveTask(this.draggedTask, targetColumnId);
            }
        },
        
        init() {
            // Add drag over event listeners
            document.querySelectorAll('.kanban-column').forEach(column => {
                column.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    column.classList.add('drop-zone');
                });
                
                column.addEventListener('dragleave', (e) => {
                    if (!column.contains(e.relatedTarget)) {
                        column.classList.remove('drop-zone');
                    }
                });
            });
        }
    }));
});

Alpine.start();
