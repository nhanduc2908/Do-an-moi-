import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../core/constants/role_constants.dart';
import '../../../core/routes/route_names.dart';
import '../../providers/auth_provider.dart';
import '../../widgets/common/custom_button.dart';

class RoleSelectionScreen extends ConsumerStatefulWidget {
  const RoleSelectionScreen({super.key});

  @override
  ConsumerState<RoleSelectionScreen> createState() => _RoleSelectionScreenState();
}

class _RoleSelectionScreenState extends ConsumerState<RoleSelectionScreen> {
  String? _selectedRole;

  final List<Map<String, dynamic>> _roles = [
    {'name': RoleConstants.superAdmin, 'display': 'Super Administrator', 'icon': '👑', 'color': '#DC3545'},
    {'name': RoleConstants.admin, 'display': 'Administrator', 'icon': '⚙️', 'color': '#E74C3C'},
    {'name': RoleConstants.securityManager, 'display': 'Security Manager', 'icon': '🛡️', 'color': '#E67E22'},
    {'name': RoleConstants.complianceOfficer, 'display': 'Compliance Officer', 'icon': '📋', 'color': '#2ECC71'},
    {'name': RoleConstants.riskManager, 'display': 'Risk Manager', 'icon': '📊', 'color': '#F39C12'},
    {'name': RoleConstants.securityAnalyst, 'display': 'Security Analyst', 'icon': '🔍', 'color': '#3498DB'},
    {'name': RoleConstants.incidentResponder, 'display': 'Incident Responder', 'icon': '🚨', 'color': '#E84393'},
    {'name': RoleConstants.vulnerabilityScanner, 'display': 'Vulnerability Scanner', 'icon': '🔬', 'color': '#1ABC9C'},
    {'name': RoleConstants.auditor, 'display': 'Auditor', 'icon': '📜', 'color': '#95A5A6'},
    {'name': RoleConstants.viewer, 'display': 'Viewer', 'icon': '👁️', 'color': '#7F8C8D'},
  ];

  Future<void> _selectRole() async {
    if (_selectedRole == null) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Please select a role')),
      );
      return;
    }

    // Update user role
    await ref.read(authProvider.notifier).updateRole(_selectedRole!);
    
    final route = _getDashboardRoute(_selectedRole!);
    context.go(route);
  }

  String _getDashboardRoute(String role) {
    switch (role) {
      case RoleConstants.superAdmin: return RouteNames.superAdminDashboard;
      case RoleConstants.admin: return RouteNames.adminDashboard;
      case RoleConstants.securityManager: return RouteNames.securityManagerDashboard;
      case RoleConstants.complianceOfficer: return RouteNames.complianceOfficerDashboard;
      case RoleConstants.riskManager: return RouteNames.riskManagerDashboard;
      case RoleConstants.securityAnalyst: return RouteNames.securityAnalystDashboard;
      case RoleConstants.incidentResponder: return RouteNames.incidentResponderDashboard;
      case RoleConstants.vulnerabilityScanner: return RouteNames.vulnerabilityScannerDashboard;
      case RoleConstants.auditor: return RouteNames.auditorDashboard;
      default: return RouteNames.viewerDashboard;
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text('Select Your Role')),
      body: Column(
        children: [
          const SizedBox(height: 20),
          const Text(
            'Choose your role to continue',
            style: TextStyle(fontSize: 16, color: Colors.grey),
          ),
          const SizedBox(height: 20),
          Expanded(
            child: ListView.builder(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              itemCount: _roles.length,
              itemBuilder: (context, index) {
                final role = _roles[index];
                final isSelected = _selectedRole == role['name'];
                return Card(
                  margin: const EdgeInsets.only(bottom: 12),
                  color: isSelected ? Colors.blue.withOpacity(0.1) : null,
                  child: ListTile(
                    leading: Text(role['icon'], style: const TextStyle(fontSize: 32)),
                    title: Text(role['display'], style: const TextStyle(fontWeight: FontWeight.bold)),
                    subtitle: Text('Level ${RoleConstants.roleLevels[role['name']]}'),
                    trailing: isSelected ? const Icon(Icons.check_circle, color: Colors.blue) : null,
                    onTap: () => setState(() => _selectedRole = role['name']),
                  ),
                );
              },
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(16.0),
            child: CustomButton(
              text: 'Continue',
              onPressed: _selectRole,
            ),
          ),
        ],
      ),
    );
  }
}