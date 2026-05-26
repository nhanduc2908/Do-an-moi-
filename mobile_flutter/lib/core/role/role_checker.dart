import '../constants/role_constants.dart';

class RoleChecker {
  static bool isSuperAdmin(String? role) => role == RoleConstants.superAdmin;
  static bool isAdmin(String? role) => role == RoleConstants.admin || isSuperAdmin(role);
  static bool isSecurityManager(String? role) => role == RoleConstants.securityManager || isAdmin(role);
  static bool isComplianceOfficer(String? role) => role == RoleConstants.complianceOfficer || isAdmin(role);
  static bool isRiskManager(String? role) => role == RoleConstants.riskManager || isAdmin(role);
  static bool isSecurityAnalyst(String? role) => role == RoleConstants.securityAnalyst || isSecurityManager(role);
  static bool isIncidentResponder(String? role) => role == RoleConstants.incidentResponder || isSecurityManager(role);
  static bool isVulnerabilityScanner(String? role) => role == RoleConstants.vulnerabilityScanner || isSecurityAnalyst(role);
  static bool isAuditor(String? role) => role == RoleConstants.auditor || isAdmin(role);
  static bool isViewer(String? role) => role == RoleConstants.viewer || true;
  
  static int getRoleLevel(String? role) => RoleConstants.roleLevels[role] ?? 0;
  static bool hasHigherLevel(String? role1, String? role2) => getRoleLevel(role1) > getRoleLevel(role2);
  static bool hasLowerLevel(String? role1, String? role2) => getRoleLevel(role1) < getRoleLevel(role2);
  
  static String getDashboardRoute(String? role) {
    switch (role) {
      case RoleConstants.superAdmin: return '/super-admin';
      case RoleConstants.admin: return '/admin';
      case RoleConstants.securityManager: return '/security-manager';
      case RoleConstants.complianceOfficer: return '/compliance-officer';
      case RoleConstants.riskManager: return '/risk-manager';
      case RoleConstants.securityAnalyst: return '/security-analyst';
      case RoleConstants.incidentResponder: return '/incident-responder';
      case RoleConstants.vulnerabilityScanner: return '/vulnerability-scanner';
      case RoleConstants.auditor: return '/auditor';
      default: return '/viewer';
    }
  }
}