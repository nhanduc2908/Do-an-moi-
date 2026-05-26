import '../constants/role_constants.dart';
import 'route_names.dart';

class RoleRoutes {
  static const Map<String, String> roleToRoute = {
    RoleConstants.superAdmin: RouteNames.superAdminDashboard,
    RoleConstants.admin: RouteNames.adminDashboard,
    RoleConstants.securityManager: RouteNames.securityManagerDashboard,
    RoleConstants.complianceOfficer: RouteNames.complianceOfficerDashboard,
    RoleConstants.riskManager: RouteNames.riskManagerDashboard,
    RoleConstants.securityAnalyst: RouteNames.securityAnalystDashboard,
    RoleConstants.incidentResponder: RouteNames.incidentResponderDashboard,
    RoleConstants.vulnerabilityScanner: RouteNames.vulnerabilityScannerDashboard,
    RoleConstants.auditor: RouteNames.auditorDashboard,
    RoleConstants.viewer: RouteNames.viewerDashboard,
  };
  
  static String getRouteForRole(String role) {
    return roleToRoute[role] ?? RouteNames.viewerDashboard;
  }
  
  static List<String> getAllRoleRoutes() {
    return roleToRoute.values.toList();
  }
  
  static bool isRoleRoute(String routeName) {
    return roleToRoute.containsValue(routeName);
  }
  
  static String? getRoleFromRoute(String routeName) {
    return roleToRoute.entries.firstWhere(
      (entry) => entry.value == routeName,
      orElse: () => const MapEntry('', ''),
    ).key;
  }
}