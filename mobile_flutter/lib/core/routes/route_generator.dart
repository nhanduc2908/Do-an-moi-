import 'package:flutter/material.dart';
import 'route_names.dart';
import '../../presentation/screens/splash_screen.dart';
import '../../presentation/screens/auth/login_screen.dart';
import '../../presentation/screens/auth/register_screen.dart';
import '../../presentation/screens/unauthorized_screen.dart';
import '../../presentation/screens/role_based/super_admin/super_admin_dashboard.dart';
import '../../presentation/screens/role_based/admin/admin_dashboard.dart';
import '../../presentation/screens/role_based/security_manager/security_manager_dashboard.dart';
import '../../presentation/screens/role_based/compliance_officer/compliance_officer_dashboard.dart';
import '../../presentation/screens/role_based/risk_manager/risk_manager_dashboard.dart';
import '../../presentation/screens/role_based/security_analyst/security_analyst_dashboard.dart';
import '../../presentation/screens/role_based/incident_responder/incident_responder_dashboard.dart';
import '../../presentation/screens/role_based/vulnerability_scanner/vulnerability_scanner_dashboard.dart';
import '../../presentation/screens/role_based/auditor/auditor_dashboard.dart';
import '../../presentation/screens/role_based/viewer/viewer_dashboard.dart';

class RouteGenerator {
  static Route<dynamic> generateRoute(RouteSettings settings) {
    switch (settings.name) {
      case RouteNames.splash:
        return MaterialPageRoute(builder: (_) => const SplashScreen());
      case RouteNames.login:
        return MaterialPageRoute(builder: (_) => const LoginScreen());
      case RouteNames.register:
        return MaterialPageRoute(builder: (_) => const RegisterScreen());
      case RouteNames.superAdminDashboard:
        return MaterialPageRoute(builder: (_) => const SuperAdminDashboard());
      case RouteNames.adminDashboard:
        return MaterialPageRoute(builder: (_) => const AdminDashboard());
      case RouteNames.securityManagerDashboard:
        return MaterialPageRoute(builder: (_) => const SecurityManagerDashboard());
      case RouteNames.complianceOfficerDashboard:
        return MaterialPageRoute(builder: (_) => const ComplianceOfficerDashboard());
      case RouteNames.riskManagerDashboard:
        return MaterialPageRoute(builder: (_) => const RiskManagerDashboard());
      case RouteNames.securityAnalystDashboard:
        return MaterialPageRoute(builder: (_) => const SecurityAnalystDashboard());
      case RouteNames.incidentResponderDashboard:
        return MaterialPageRoute(builder: (_) => const IncidentResponderDashboard());
      case RouteNames.vulnerabilityScannerDashboard:
        return MaterialPageRoute(builder: (_) => const VulnerabilityScannerDashboard());
      case RouteNames.auditorDashboard:
        return MaterialPageRoute(builder: (_) => const AuditorDashboard());
      case RouteNames.viewerDashboard:
        return MaterialPageRoute(builder: (_) => const ViewerDashboard());
      case RouteNames.unauthorized:
        return MaterialPageRoute(builder: (_) => const UnauthorizedScreen());
      default:
        return MaterialPageRoute(builder: (_) => const UnauthorizedScreen());
    }
  }
}