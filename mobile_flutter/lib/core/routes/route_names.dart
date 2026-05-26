class RouteNames {
  static const String splash = '/';
  static const String onboarding = '/onboarding';
  static const String login = '/login';
  static const String register = '/register';
  static const String forgotPassword = '/forgot-password';
  static const String twoFactor = '/two-factor';
  
  static const String superAdminDashboard = '/super-admin';
  static const String adminDashboard = '/admin';
  static const String securityManagerDashboard = '/security-manager';
  static const String complianceOfficerDashboard = '/compliance-officer';
  static const String riskManagerDashboard = '/risk-manager';
  static const String securityAnalystDashboard = '/security-analyst';
  static const String incidentResponderDashboard = '/incident-responder';
  static const String vulnerabilityScannerDashboard = '/vulnerability-scanner';
  static const String auditorDashboard = '/auditor';
  static const String viewerDashboard = '/viewer';
  
  static const String assessments = '/assessments';
  static const String assessmentDetail = '/assessments/:id';
  static const String assessmentCreate = '/assessments/create';
  static const String assessmentSubmit = '/assessments/:id/submit';
  
  static const String incidents = '/incidents';
  static const String incidentDetail = '/incidents/:id';
  static const String incidentCreate = '/incidents/create';
  
  static const String vulnerabilities = '/vulnerabilities';
  static const String vulnerabilityDetail = '/vulnerabilities/:id';
  
  static const String reports = '/reports';
  static const String reportDetail = '/reports/:id';
  static const String reportGenerate = '/reports/generate';
  
  static const String profile = '/profile';
  static const String settings = '/settings';
  static const String notifications = '/notifications';
  
  static const String unauthorized = '/unauthorized';
}