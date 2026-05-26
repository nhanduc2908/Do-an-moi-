import '../constants/role_constants.dart';

class RolePermissions {
  static const Map<String, List<String>> permissions = {
    RoleConstants.superAdmin: ['*'],
    RoleConstants.admin: [
      'user.view', 'user.create', 'user.edit', 'user.delete',
      'role.view', 'role.create', 'role.edit', 'role.delete',
      'system.view', 'system.edit', 'audit.view', 'backup.view',
    ],
    RoleConstants.securityManager: [
      'incident.view', 'incident.create', 'incident.edit', 'incident.delete',
      'incident.assign', 'incident.resolve', 'vulnerability.view',
      'vulnerability.scan', 'assessment.view', 'team.view',
    ],
    RoleConstants.complianceOfficer: [
      'compliance.view', 'compliance.check', 'compliance.report',
      'audit.view', 'audit.run', 'evidence.view', 'evidence.upload',
    ],
    RoleConstants.riskManager: [
      'risk.view', 'risk.create', 'risk.edit', 'risk.delete',
      'risk.assess', 'risk.treat', 'assessment.view', 'report.view',
    ],
    RoleConstants.securityAnalyst: [
      'incident.view', 'vulnerability.view', 'vulnerability.scan',
      'threat.view', 'report.view',
    ],
    RoleConstants.incidentResponder: [
      'incident.view', 'incident.update', 'incident.resolve',
      'forensic.view', 'recovery.view',
    ],
    RoleConstants.vulnerabilityScanner: [
      'vulnerability.view', 'vulnerability.scan', 'report.view',
    ],
    RoleConstants.auditor: [
      'audit.view', 'audit.export', 'report.view', 'report.export',
    ],
    RoleConstants.viewer: ['dashboard.view', 'report.view'],
  };
  
  static bool hasPermission(String? role, String permission) {
    if (role == null) return false;
    if (role == RoleConstants.superAdmin) return true;
    
    final rolePermissions = permissions[role] ?? [];
    if (rolePermissions.contains(permission)) return true;
    
    for (final rp in rolePermissions) {
      if (rp.endsWith('.*') && permission.startsWith(rp.substring(0, rp.length - 2))) {
        return true;
      }
    }
    return false;
  }
}