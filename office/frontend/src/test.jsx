import  { useState } from 'react';
import axios from 'axios';
import './test.css'

const TaskForm = () => {
  const [taskName, setTaskName] = useState('');
  const [description, setDescription] = useState('');
  const [dueDate, setDueDate] = useState('');
  const [finDate, setFinDate] = useState('');
  const [divisionId, setDivisionId] = useState('');

  const handleSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await axios.post('http://127.0.0.1:8000/api/v1/tasks/', {
        task_name: taskName,
        description:description,
        due_date: dueDate,
        fin_date: finDate,
        division_id: divisionId,
      });
      console.log('Task created:', response.data);
    } catch (error) {
      console.error('There was an error creating the task:', error);
    }
  };

  return (
    <div className="form-container">
      <h2 className="form-title">Create New Task</h2>
      <form onSubmit={handleSubmit}>
        <div className="form-group">
          <label className="form-label">Task Name</label>
          <input
            type="text"
            className="form-input"
            value={taskName}
            onChange={(e) => setTaskName(e.target.value)}
            required
          />
        </div>

        <div className="form-group">
          <label className="form-label">Description</label>
          <textarea
            className="form-input form-textarea"
            value={description}
            onChange={(e) => setDescription(e.target.value)}
          />
        </div>

        <div className="date-inputs">
          <div className="form-group">
            <label className="form-label">Due Date</label>
            <input
              type="date"
              className="form-input"
              value={dueDate}
              onChange={(e) => setDueDate(e.target.value)}
              required
            />
          </div>

          <div className="form-group">
            <label className="form-label">Finish Date</label>
            <input
              type="date"
              className="form-input"
              value={finDate}
              onChange={(e) => setFinDate(e.target.value)}
            />
          </div>
        </div>

        <div className="form-group">
          <label className="form-label">Division ID</label>
          <input
            type="number"
            className="form-input"
            value={divisionId}
            onChange={(e) => setDivisionId(e.target.value)}
            required
          />
        </div>

        <button type="submit" className="submit-button">
          Create Task
        </button>
      </form>
    </div>
  );
};

export default TaskForm;
