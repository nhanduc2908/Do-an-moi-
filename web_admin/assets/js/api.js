/**
 * API JavaScript - Xử lý các cuộc gọi API
 */

// API Base URL
const API_BASE_URL = '/api';

// API Client
const ApiClient = {
    async request(endpoint, options = {}) {
        const defaultOptions = {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        };
        
        const mergedOptions = { ...defaultOptions, ...options };
        
        try {
            const response = await fetch(`${API_BASE_URL}${endpoint}`, mergedOptions);
            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || 'API request failed');
            }
            
            return data;
        } catch (error) {
            console.error('API Error:', error);
            throw error;
        }
    },
    
    get(endpoint, params = {}) {
        const queryString = new URLSearchParams(params).toString();
        const url = queryString ? `${endpoint}?${queryString}` : endpoint;
        return this.request(url, { method: 'GET' });
    },
    
    post(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    },
    
    put(endpoint, data = {}) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },
    
    delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }
};

// User API
const UserApi = {
    getUsers(params = {}) {
        return ApiClient.get('/users', params);
    },
    
    getUser(id) {
        return ApiClient.get(`/users/${id}`);
    },
    
    createUser(data) {
        return ApiClient.post('/users', data);
    },
    
    updateUser(id, data) {
        return ApiClient.put(`/users/${id}`, data);
    },
    
    deleteUser(id) {
        return ApiClient.delete(`/users/${id}`);
    }
};

// Assessment API
const AssessmentApi = {
    getAssessments(params = {}) {
        return ApiClient.get('/assessments', params);
    },
    
    getAssessment(id) {
        return ApiClient.get(`/assessments/${id}`);
    },
    
    createAssessment(data) {
        return ApiClient.post('/assessments', data);
    },
    
    updateAssessment(id, data) {
        return ApiClient.put(`/assessments/${id}`, data);
    },
    
    deleteAssessment(id) {
        return ApiClient.delete(`/assessments/${id}`);
    },
    
    submitAssessment(id, answers) {
        return ApiClient.post(`/assessments/${id}/submit`, { answers });
    }
};

// Incident API
const IncidentApi = {
    getIncidents(params = {}) {
        return ApiClient.get('/incidents', params);
    },
    
    getIncident(id) {
        return ApiClient.get(`/incidents/${id}`);
    },
    
    createIncident(data) {
        return ApiClient.post('/incidents', data);
    },
    
    updateIncident(id, data) {
        return ApiClient.put(`/incidents/${id}`, data);
    },
    
    resolveIncident(id, resolution) {
        return ApiClient.post(`/incidents/${id}/resolve`, { resolution });
    }
};

// Report API
const ReportApi = {
    getReports(params = {}) {
        return ApiClient.get('/reports', params);
    },
    
    generateReport(data) {
        return ApiClient.post('/reports/generate', data);
    },
    
    downloadReport(id) {
        window.open(`${API_BASE_URL}/reports/${id}/download`, '_blank');
    },
    
    shareReport(id, recipients) {
        return ApiClient.post(`/reports/${id}/share`, { recipients });
    }
};

// Dashboard API
const DashboardApi = {
    getStats() {
        return ApiClient.get('/dashboard/stats');
    },
    
    getSecurityScore() {
        return ApiClient.get('/dashboard/security-score');
    },
    
    getRecentActivities() {
        return ApiClient.get('/dashboard/recent-activities');
    }
};

// Export APIs
window.ApiClient = ApiClient;
window.UserApi = UserApi;
window.AssessmentApi = AssessmentApi;
window.IncidentApi = IncidentApi;
window.ReportApi = ReportApi;
window.DashboardApi = DashboardApi;