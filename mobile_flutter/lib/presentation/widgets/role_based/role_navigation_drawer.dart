// Đường dẫn: mobile_flutter/lib/presentation/widgets/role_based/role_navigation_drawer.dart

import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/constants/role_constants.dart';
import '../../../core/routes/route_names.dart';
import '../../providers/auth_provider.dart';
import '../../providers/role_provider.dart';

class RoleNavigationDrawer extends ConsumerWidget {
  const RoleNavigationDrawer({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final user = ref.watch(authProvider).user;
    final role = ref.watch(roleProvider);
    final displayName = ref.watch(roleDisplayNameProvider);
    final icon = ref.watch(roleIconProvider);
    final color = ref.watch(roleColorProvider);

    return Drawer(
      child: Column(
        children: [
          Container(
            width: double.infinity,
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              gradient: LinearGradient(
                colors: [
                  Color(int.parse(color.substring(1, 7), radix: 16) + 0xFF000000),
                  Color(int.parse(color.substring(1, 7), radix: 16) + 0xFF000000).withOpacity(0.7),
                ],
                begin: Alignment.topLeft,
                end: Alignment.bottomRight,
              ),
            ),
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                CircleAvatar(
                  radius: 30,
                  backgroundColor: Colors.white,
                  child: Text(
                    icon,
                    style: const TextStyle(fontSize: 28),
                  ),
                ),
                const SizedBox(height: 12),
                Text(
                  user?.name ?? 'User',
                  style: const TextStyle(
                    color: Colors.white,
                    fontSize: 18,
                    fontWeight: FontWeight.bold,
                  ),
                ),
                const SizedBox(height: 4),
                Text(
                  displayName,
                  style: const TextStyle(color: Colors.white70, fontSize: 14),
                ),
                const SizedBox(height: 4),
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 2),
                  decoration: BoxDecoration(
                    color: Colors.white.withOpacity(0.2),
                    borderRadius: BorderRadius.circular(12),
                  ),
                  child: Text(
                    'Level ${RoleConstants.roleLevels[role] ?? 0}',
                    style: const TextStyle(color: Colors.white70, fontSize: 10),
                  ),
                ),
              ],
            ),
          ),
          Expanded(
            child: ListView(
              padding: EdgeInsets.zero,
              children: [
                _buildDrawerItem(
                  context,
                  icon: Icons.dashboard,
                  title: 'Dashboard',
                  route: _getDashboardRoute(role),
                ),
                _buildDrawerItem(
                  context,
                  icon: Icons.security,
                  title: 'Security Score',
                  route: RouteNames.securityScore,
                ),
                if (_showAssessments(role))
                  _buildDrawerItem(
                    context,
                    icon: Icons.assessment,
                    title: 'Assessments',
                    route: RouteNames.assessments,
                  ),
                if (_showIncidents(role))
                  _buildDrawerItem(
                    context,
                    icon: Icons.warning,
                    title: 'Incidents',
                    route: RouteNames.incidents,
                  ),
                if (_showVulnerabilities(role))
                  _buildDrawerItem(
                    context,
                    icon: Icons.bug_report,
                    title: 'Vulnerabilities',
                    route: RouteNames.vulnerabilities,
                  ),
                if (_showCompliance(role))
                  _buildDrawerItem(
                    context,
                    icon: Icons.checklist,
                    title: 'Compliance',
                    route: RouteNames.compliance,
                  ),
                if (_showReports(role))
                  _buildDrawerItem(
                    context,
                    icon: Icons.description,
                    title: 'Reports',
                    route: RouteNames.reports,
                  ),
                if (_showAI(role))
                  _buildDrawerItem(
                    context,
                    icon: Icons.psychology,
                    title: 'AI Assistant',
                    route: RouteNames.aiDashboard,
                  ),
                if (_showAdmin(role))
                  _buildDrawerItem(
                    context,
                    icon: Icons.admin_panel_settings,
                    title: 'Admin Panel',
                    route: RouteNames.adminUsers,
                  ),
                const Divider(),
                _buildDrawerItem(
                  context,
                  icon: Icons.person,
                  title: 'Profile',
                  route: RouteNames.profile,
                ),
                _buildDrawerItem(
                  context,
                  icon: Icons.settings,
                  title: 'Settings',
                  route: RouteNames.settings,
                ),
                _buildDrawerItem(
                  context,
                  icon: Icons.help,
                  title: 'Help & Support',
                  route: '/help',
                ),
                const Divider(),
                ListTile(
                  leading: const Icon(Icons.logout, color: Colors.red),
                  title: const Text('Logout', style: TextStyle(color: Colors.red)),
                  onTap: () => _logout(context, ref),
                ),
              ],
            ),
          ),
          Container(
            padding: const EdgeInsets.all(16),
            decoration: BoxDecoration(
              border: Border(top: BorderSide(color: Colors.grey.shade200)),
            ),
            child: Row(
              children: [
                const Icon(Icons.info_outline, size: 16, color: Colors.grey),
                const SizedBox(width: 8),
                Text(
                  'Version ${_getVersion()}',
                  style: const TextStyle(fontSize: 12, color: Colors.grey),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDrawerItem(BuildContext context, {
    required IconData icon,
    required String title,
    required String route,
  }) {
    return ListTile(
      leading: Icon(icon),
      title: Text(title),
      onTap: () {
        Navigator.pop(context);
        Navigator.pushNamed(context, route);
      },
    );
  }

  String _getDashboardRoute(String? role) {
    switch (role) {
      case RoleConstants.superAdmin:
        return RouteNames.superAdminDashboard;
      case RoleConstants.admin:
        return RouteNames.adminDashboard;
      case RoleConstants.securityManager:
        return RouteNames.securityManagerDashboard;
      case RoleConstants.complianceOfficer:
        return RouteNames.complianceOfficerDashboard;
      case RoleConstants.riskManager:
        return RouteNames.riskManagerDashboard;
      case RoleConstants.securityAnalyst:
        return RouteNames.securityAnalystDashboard;
      case RoleConstants.incidentResponder:
        return RouteNames.incidentResponderDashboard;
      case RoleConstants.vulnerabilityScanner:
        return RouteNames.vulnerabilityScannerDashboard;
      case RoleConstants.auditor:
        return RouteNames.auditorDashboard;
      default:
        return RouteNames.viewerDashboard;
    }
  }

  bool _showAssessments(String? role) {
    return role != RoleConstants.auditor && role != RoleConstants.viewer;
  }

  bool _showIncidents(String? role) {
    return role == RoleConstants.superAdmin ||
           role == RoleConstants.admin ||
           role == RoleConstants.securityManager ||
           role == RoleConstants.securityAnalyst ||
           role == RoleConstants.incidentResponder;
  }

  bool _showVulnerabilities(String? role) {
    return role == RoleConstants.superAdmin ||
           role == RoleConstants.admin ||
           role == RoleConstants.securityManager ||
           role == RoleConstants.securityAnalyst ||
           role == RoleConstants.vulnerabilityScanner;
  }

  bool _showCompliance(String? role) {
    return role == RoleConstants.superAdmin ||
           role == RoleConstants.admin ||
           role == RoleConstants.complianceOfficer ||
           role == RoleConstants.auditor;
  }

  bool _showReports(String? role) {
    return role != RoleConstants.viewer;
  }

  bool _showAI(String? role) {
    return role == RoleConstants.superAdmin ||
           role == RoleConstants.admin ||
           role == RoleConstants.securityAnalyst;
  }

  bool _showAdmin(String? role) {
    return role == RoleConstants.superAdmin || role == RoleConstants.admin;
  }

  void _logout(BuildContext context, WidgetRef ref) {
    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('Logout'),
        content: const Text('Are you sure you want to logout?'),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('Cancel'),
          ),
          TextButton(
            onPressed: () async {
              await ref.read(authProvider.notifier).logout();
              if (context.mounted) {
                Navigator.of(context).pushNamedAndRemoveUntil(
                  RouteNames.login,
                  (route) => false,
                );
              }
            },
            child: const Text('Logout', style: TextStyle(color: Colors.red)),
          ),
        ],
      ),
    );
  }

  String _getVersion() {
    return '1.0.0';
  }
}