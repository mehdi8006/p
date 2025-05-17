import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import '../../../styles/taches.css';

export default function Add() {
  const navigate = useNavigate();
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    startDate: '',
    endDate: '',
    divisionId: '',
    file: null
  });
  const [isSaving, setIsSaving] = useState(false);
  const [divisions, setDivisions] = useState([]);
  const [error, setError] = useState('');

  // Fetch divisions on component mount
  useState(() => {
    axios.get('http://127.0.0.1:8000/api/v1/divisions')
      .then(response => {
        if (response.data && response.data.length > 0) {
          setDivisions(response.data);
        }
      })
      .catch(error => {
        console.error('Error fetching divisions:', error);
        setError('Failed to load divisions. Please try again later.');
      });
  }, []);

  const handleChange = e => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
  };

  const handleFileChange = e => {
    setFormData(prev => ({ ...prev, file: e.target.files[0] }));
  };

  const handleDivisionSelect = (divId) => {
    setFormData(prev => ({
      ...prev,
      divisionId: prev.divisionId === divId ? '' : divId
    }));
  };

  const handleSubmit = e => {
    e.preventDefault();
    setIsSaving(true);
    setError('');

    if (!formData.divisionId) {
      setError('Please select a division.');
      setIsSaving(false);
      return;
    }

    if (!formData.file) {
      setError('Please select a document file.');
      setIsSaving(false);
      return;
    }

    const today = new Date().toISOString().split('T')[0];
    const payload = new FormData();
    payload.append('task_name', formData.title);
    payload.append('description', formData.description);
    payload.append('due_date', formData.startDate);
    payload.append('fin_date', formData.endDate || null);
    payload.append('division_id', formData.divisionId);

    axios.post('http://127.0.0.1:8000/api/v1/tasks', payload)
      .then(response => {
        const taskId = response.data.data.task_id;

        // Create status
        return axios.post('http://127.0.0.1:8000/api/v1/statuses', {
          task_id: taskId,
          statut: 'En attente',
          date_changed: today,
        })
        .then(() => taskId);
      })
      .then(taskId => {
        // Create document path with the actual file
        const docPayload = new FormData();
        docPayload.append('document_path', formData.file);
        docPayload.append('task_id', taskId);

        return axios.post('http://127.0.0.1:8000/api/v1/documentpaths', docPayload, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
      })
      .then(() => {
        alert(`Task created successfully!`);
        navigate('/app');
      })
      .catch(error => {
        console.error('Error:', error.response?.data || error.message);
        setError(error.response?.data?.message || 'Task creation failed');
      })
      .finally(() => {
        setIsSaving(false);
      });
  };

  return (
    <div className="add-task-container">
      <div className="page-header">
        <h1>Add New Task</h1>
      </div>

      {error && <div className="error-message">{error}</div>}

      <form onSubmit={handleSubmit} className="task-form">
        <div className="form-section">
          <h2>Task Details</h2>
          <div className="form-group">
            <label>Title*</label>
            <input
              type="text"
              name="title"
              value={formData.title}
              onChange={handleChange}
              required
              placeholder="Enter task title"
            />
          </div>
          <div className="form-group">
            <label>Description*</label>
            <textarea
              name="description"
              value={formData.description}
              onChange={handleChange}
              required
              rows={4}
              placeholder="Describe the task in detail"
            />
          </div>
        </div>
        
        <div className="form-section">
          <h2>Division Assignment</h2>
          <div className="form-group">
            <label>Select Division*</label>
            <div className="division-selector">
              {divisions.map((div) => (
                <div
                  key={div.division_id}
                  className={`division-tag ${
                    formData.divisionId === div.division_id ? 'selected' : ''
                  }`}
                  onClick={() => handleDivisionSelect(div.division_id)}
                >
                  {div.division_nom}
                  {formData.divisionId === div.division_id && (
                    <span className="responsable-badge">
                      {div.division_responsable}
                    </span>
                  )}
                </div>
              ))}
            </div>
          </div>
        </div>

        <div className="form-section">
          <h2>Task Timeline</h2>
          <div className="form-row">
            <div className="form-group">
              <label>Start Date*</label>
              <input
                type="date"
                name="startDate"
                value={formData.startDate}
                onChange={handleChange}
                required
                min={new Date().toISOString().split('T')[0]}
              />
            </div>
            <div className="form-group">
              <label>End Date</label>
              <input
                type="date"
                name="endDate"
                value={formData.endDate}
                onChange={handleChange}
                min={formData.startDate || new Date().toISOString().split('T')[0]}
              />
            </div>
          </div>
        </div>

        <div className="form-group">
          <label>Documentation*</label>
          <input 
            type="file" 
            onChange={handleFileChange} 
            accept=".pdf,.doc,.docx,.txt"
            required
          />
        </div>

        <div className="form-actions">
          <button type="button" className="cancel-btn" onClick={() => navigate('/app')}>
            Cancel
          </button>
          <button type="submit" className="submit-btn" disabled={isSaving}>
            {isSaving ? "Saving..." : "Save Task"}
          </button>
        </div>
      </form>
    </div>
  );
}