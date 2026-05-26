class RoleConstants {
  static const String superAdmin = 'super_admin';
  static const String admin = 'admin';
  static const String securityManager = 'security_manager';
  static const String complianceOfficer = 'compliance_officer';
  static const String riskManager = 'risk_manager';
  static const String securityAnalyst = 'security_analyst';
  static const String incidentResponder = 'incident_responder';
  static const String vulnerabilityScanner = 'vulnerability_scanner';
  static const String auditor = 'auditor';
  static const String viewer = 'viewer';

  static const List<String> allRoles = [
    superAdmin, admin, securityManager, complianceOfficer, riskManager,
    securityAnalyst, incidentResponder, vulnerabilityScanner, auditor, viewer,
  ];

  static const Map<String, int> roleLevels = {
    superAdmin: 100, admin: 90, securityManager: 80, riskManager: 75,
    complianceOfficer: 70, securityAnalyst: 60, incidentResponder: 55,
    vulnerabilityScanner: 45, auditor: 50, viewer: 10,
  };

  static const Map<String, String> roleDisplayNames = {
    superAdmin: 'Super Administrator', admin: 'Administrator',
    securityManager: 'Security Manager', complianceOfficer: 'Compliance Officer',
    riskManager: 'Risk Manager', securityAnalyst: 'Security Analyst',
    incidentResponder: 'Incident Responder', vulnerabilityScanner: 'Vulnerability Scanner',
    auditor: 'Auditor', viewer: 'Viewer',
  };

  static const Map<String, String> roleIcons = {
    superAdmin: '👑', admin: '⚙️', securityManager: '🛡️', complianceOfficer: '📋',
    riskManager: '📊', securityAnalyst: '🔍', incidentResponder: '🚨',
    vulnerabilityScanner: '🔬', auditor: '📜', viewer: '👁️',
  };

  static const Map<String, String> roleColors = {
    superAdmin: '#DC3545', admin: '#E74C3C', securityManager: '#E67E22',
    complianceOfficer: '#2ECC71', riskManager: '#F39C12', securityAnalyst: '#3498DB',
    incidentResponder: '#E84393', vulnerabilityScanner: '#1ABC9C',
    auditor: '#95A5A6', viewer: '#7F8C8D',
  };
}