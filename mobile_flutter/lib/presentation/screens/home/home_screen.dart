import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../role_based/super_admin/super_admin_dashboard.dart';
import '../role_based/admin/admin_dashboard.dart';
import '../role_based/security_manager/security_manager_dashboard.dart';
import '../role_based/compliance_officer/compliance_officer_dashboard.dart';
import '../role_based/risk_manager/risk_manager_dashboard.dart';
import '../role_based/security_analyst/security_analyst_dashboard.dart';
import '../role_based/incident_responder/incident_responder_dashboard.dart';
import '../role_based/vulnerability_scanner/vulnerability_scanner_dashboard.dart';
import '../role_based/auditor/auditor_dashboard.dart';
import '../role_based/viewer/viewer_dashboard.dart';
import '../../providers/role_provider.dart';

class HomeScreen extends ConsumerWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final role = ref.watch(roleProvider);
    
    switch (role) {
      case 'super_admin':
        return const SuperAdminDashboard();
      case 'admin':
        return const AdminDashboard();
      case 'security_manager':
        return const SecurityManagerDashboard();
      case 'compliance_officer':
        return const ComplianceOfficerDashboard();
      case 'risk_manager':
        return const RiskManagerDashboard();
      case 'security_analyst':
        return const SecurityAnalystDashboard();
      case 'incident_responder':
        return const IncidentResponderDashboard();
      case 'vulnerability_scanner':
        return const VulnerabilityScannerDashboard();
      case 'auditor':
        return const AuditorDashboard();
      default:
        return const ViewerDashboard();
    }
  }
}