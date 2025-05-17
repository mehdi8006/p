import React, { useState } from 'react';
import './stylesres/TaskManagment.css'
const TaskManagement = () => {
  const [tasks, setTasks] = useState([
    {
      id: 1,
      title: 'Implement user login',
      status: 'Todo',
      history: [
        {
          timestamp: '2025-04-17T09:00:00Z',
          change: 'Task created',
          from: '',
          to: 'Todo',
          user: 'System'
        }
      ]
    }
  ]);

  const statusOptions = ['Todo', 'In Progress', 'Review', 'Done'];

  const handleStatusChange = (taskId, newStatus) => {
    setTasks(prevTasks => 
      prevTasks.map(task => {
        if (task.id === taskId) {
          const historyEntry = {
            timestamp: new Date().toISOString(),
            change: 'Status changed',
            from: task.status,
            to: newStatus,
            user: 'Current User' // Replace with actual user from auth context
          };
          
          return {
            ...task,
            status: newStatus,
            history: [...task.history, historyEntry]
          };
        }
        return task;
      })
    );
  };

  return (
    <div className="task-management">
      <h2>Task Management</h2>
      <div className="task-grid">
        {tasks.map(task => (
          <div key={task.id} className="task-card-wrapper">
            <div className="task-card">
              <div className="card-body">
                <h5 className="card-title">{task.title}</h5>
                <div className="status-group">
                  <label>Status:</label>
                  <select
                    className="status-select"
                    value={task.status}
                    onChange={(e) => handleStatusChange(task.id, e.target.value)}
                  >
                    {statusOptions.map(option => (
                      <option key={option} value={option}>{option}</option>
                    ))}
                  </select>
                </div>

                <div className="task-history">
                  <h6>Change History</h6>
                  <ul className="history-list">
                    {task.history.map((entry, index) => (
                      <li key={index} className="history-item">
                        <div className="history-meta">
                          <span className="history-date">
                            {new Date(entry.timestamp).toLocaleDateString()}
                          </span>
                          <span className="history-user">{entry.user}</span>
                        </div>
                        <div className="history-change">
                          {entry.change}: {entry.from && `${entry.from} → `}{entry.to}
                        </div>
                      </li>
                    ))}
                  </ul>
                </div>
              </div>
            </div>
          </div>
        ))}
      </div>
    </div>
  );
};

export default TaskManagement;