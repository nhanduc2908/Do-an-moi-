import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
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

class AppRoutes {
  static final GoRouter router = GoRouter(
    initialLocation: RouteNames.splash,
    routes: [
      GoRoute(path: RouteNames.splash, name: 'splash', builder: (_, __) => const SplashScreen()),
      GoRoute(path: RouteNames.login, name: 'login', builder: (_, __) => const LoginScreen()),
      GoRoute(path: RouteNames.register, name: 'register', builder: (_, __) => const RegisterScreen()),
      GoRoute(path: RouteNames.unauthorized, name: 'unauthorized', builder: (_, __) => const UnauthorizedScreen()),
      
      GoRoute(path: RouteNames.superAdminDashboard, name: 'super_admin', builder: (_, __) => const SuperAdminDashboard()),
      GoRoute(path: RouteNames.adminDashboard, name: 'admin', builder: (_, __) => const AdminDashboard()),
      GoRoute(path: RouteNames.securityManagerDashboard, name: 'security_manager', builder: (_, __) => const SecurityManagerDashboard()),
      GoRoute(path: RouteNames.complianceOfficerDashboard, name: 'compliance_officer', builder: (_, __) => const ComplianceOfficerDashboard()),
      GoRoute(path: RouteNames.riskManagerDashboard, name: 'risk_manager', builder: (_, __) => const RiskManagerDashboard()),
      GoRoute(path: RouteNames.securityAnalystDashboard, name: 'security_analyst', builder: (_, __) => const SecurityAnalystDashboard()),
      GoRoute(path: RouteNames.incidentResponderDashboard, name: 'incident_responder', builder: (_, __) => const IncidentResponderDashboard()),
      GoRoute(path: RouteNames.vulnerabilityScannerDashboard, name: 'vulnerability_scanner', builder: (_, __) => const VulnerabilityScannerDashboard()),
      GoRoute(path: RouteNames.auditorDashboard, name: 'auditor', builder: (_, __) => const AuditorDashboard()),
      GoRoute(path: RouteNames.viewerDashboard, name: 'viewer', builder: (_, __) => const ViewerDashboard()),
    ],
    errorBuilder: (context, state) => const UnauthorizedScreen(),
  );
}