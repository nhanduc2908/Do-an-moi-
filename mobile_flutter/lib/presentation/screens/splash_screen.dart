import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/routes/route_names.dart';
import '../providers/auth_provider.dart';
import '../providers/role_provider.dart';

class SplashScreen extends ConsumerStatefulWidget {
  const SplashScreen({super.key});

  @override
  ConsumerState<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends ConsumerState<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _navigateToNext();
  }

  Future<void> _navigateToNext() async {
    await Future.delayed(const Duration(seconds: 2));
    
    if (!mounted) return;
    
    final isAuthenticated = ref.read(authProvider).isAuthenticated;
    final role = ref.read(roleProvider);
    
    if (isAuthenticated && role != null) {
      final route = _getDashboardRoute(role);
      context.go(route);
    } else {
      context.go(RouteNames.login);
    }
  }

  String _getDashboardRoute(String role) {
    switch (role) {
      case 'super_admin': return RouteNames.superAdminDashboard;
      case 'admin': return RouteNames.adminDashboard;
      case 'security_manager': return RouteNames.securityManagerDashboard;
      case 'compliance_officer': return RouteNames.complianceOfficerDashboard;
      case 'risk_manager': return RouteNames.riskManagerDashboard;
      case 'security_analyst': return RouteNames.securityAnalystDashboard;
      case 'incident_responder': return RouteNames.incidentResponderDashboard;
      case 'vulnerability_scanner': return RouteNames.vulnerabilityScannerDashboard;
      case 'auditor': return RouteNames.auditorDashboard;
      default: return RouteNames.viewerDashboard;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.security, size: 80, color: Colors.blue),
            const SizedBox(height: 20),
            Text(
              'Security Assessment Platform',
              style: Theme.of(context).textTheme.headlineSmall,
            ),
            const SizedBox(height: 30),
            const CircularProgressIndicator(),
          ],
        ),
      ),
    );
  }
}