class ApiConstants {
  static const String baseUrl = 'http://localhost:8000/api';
  static const String apiVersion = 'v1';
  
  // Auth
  static const String login = '/auth/login';
  static const String register = '/auth/register';
  static const String logout = '/auth/logout';
  static const String refreshToken = '/auth/refresh';
  static const String forgotPassword = '/auth/forgot-password';
  static const String resetPassword = '/auth/reset-password';
  static const String verifyMfa = '/auth/verify-mfa';
  static const String enableMfa = '/auth/mfa/enable';
  static const String disableMfa = '/auth/mfa/disable';
  
  // User
  static const String profile = '/user/profile';
  static const String updateProfile = '/user/profile/update';
  static const String changePassword = '/user/change-password';
  static const String users = '/admin/users';
  static const String userDetail = '/admin/users/{id}';
  
  // Assessment
  static const String assessments = '/assessments';
  static const String assessmentDetail = '/assessments/{id}';
  static const String submitAssessment = '/assessments/{id}/submit';
  static const String reviewAssessment = '/assessments/{id}/review';
  static const String assessmentProgress = '/assessments/{id}/progress';
  static const String exportAssessment = '/assessments/{id}/export';
  
  // Key
  static const String keys = '/keys';
  static const String keyDetail = '/keys/{id}';
  static const String verifyKey = '/keys/verify';
  static const String revokeKey = '/keys/{id}/revoke';
  static const String rotateKey = '/keys/{id}/rotate';
  
  // Report
  static const String reports = '/reports';
  static const String reportDetail = '/reports/{id}';
  static const String generateReport = '/reports/generate';
  static const String exportReport = '/reports/{id}/export';
  static const String shareReport = '/reports/{id}/share';
  
  // Incident
  static const String incidents = '/incidents';
  static const String incidentDetail = '/incidents/{id}';
  static const String resolveIncident = '/incidents/{id}/resolve';
  static const String addComment = '/incidents/{id}/comment';
  
  // Vulnerability
  static const String vulnerabilities = '/vulnerabilities';
  static const String vulnerabilityDetail = '/vulnerabilities/{id}';
  static const String scanVulnerability = '/scans/vulnerability';
  static const String remediateVulnerability = '/vulnerabilities/{id}/remediate';
  
  // Compliance
  static const String compliance = '/compliance';
  static const String iso27001 = '/compliance/iso27001';
  static const String gdpr = '/compliance/gdpr';
  static const String pciDss = '/compliance/pci-dss';
  static const String runAudit = '/compliance/audit/run';
  
  // AI
  static const String aiDetect = '/ai/detect';
  static const String aiAnomaly = '/ai/anomaly';
  static const String aiChat = '/ai/chat';
  static const String aiGenerateCriteria = '/ai/generate-criteria';
  static const String aiPredictions = '/ai/predictions';
  
  // Sync
  static const String syncFlutter = '/sync/flutter';
  static const String syncFirebase = '/sync/firebase';
  static const String syncStatus = '/sync/status';
  
  // Dashboard
  static const String dashboard = '/dashboard';
  static const String securityScore = '/dashboard/security-score';
  static const String recentActivities = '/dashboard/recent-activities';
  
  // Headers
  static const String contentType = 'Content-Type';
  static const String applicationJson = 'application/json';
  static const String authorization = 'Authorization';
  static const String bearer = 'Bearer';
  
  // Timeouts
  static const int connectTimeout = 30000;
  static const int receiveTimeout = 30000;
}