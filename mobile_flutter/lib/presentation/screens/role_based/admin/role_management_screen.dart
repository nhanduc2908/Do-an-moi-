import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../../../core/constants/role_constants.dart';
import '../../../providers/role_provider.dart';
import '../../../widgets/common/custom_button.dart';
import '../../../widgets/common/custom_textfield.dart';

class RoleManagementScreen extends ConsumerStatefulWidget {
  const RoleManagementScreen({super.key});

  @override
  ConsumerState<RoleManagementScreen> createState() => _RoleManagementScreenState();
}

class _RoleManagementScreenState extends ConsumerState<RoleManagementScreen> {
  String _selectedRole = RoleConstants.viewer;
  bool _showPermissionsDialog = false;
  
  final Map<String, List<String>> _permissions = {
    'User Management': ['user.view', 'user.create', 'user.edit', 'user.delete'],
    'Role Management': ['role.view', 'role.create', 'role.edit', 'role.delete'],
    'Assessment': ['assessment.view', 'assessment.create', 'assessment.review', 'assessment.export'],
    'Incident': ['incident.view', 'incident.create', 'incident.edit', 'incident.delete'],
    'Report': ['report.view', 'report.generate', 'report.export', 'report.share'],
    'System': ['system.view', 'system.config', 'system.backup', 'system.audit'],
  };
  
  Map<String, List<bool>> _selectedPermissions = {};

  @override
  void initState() {
    super.initState();
    _initPermissions();
  }

  void _initPermissions() {
    for (final category in _permissions.keys) {
      _selectedPermissions[category] = List.generate(_permissions[category]!.length, (_) => false);
    }
  }

  void _openPermissionsDialog(String role) {
    setState(() {
      _selectedRole = role;
      _showPermissionsDialog = true;
    });
  }

  Widget _buildRoleCard(String role) {
    final displayName = RoleConstants.roleDisplayNames[role] ?? role;
    final icon = RoleConstants.roleIcons[role] ?? '👤';
    final color = RoleConstants.roleColors[role] ?? '#6c757d';
    final level = RoleConstants.roleLevels[role] ?? 0;
    
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: ListTile(
        leading: Container(
          width: 50,
          height: 50,
          decoration: BoxDecoration(
            color: Color(int.parse(color.substring(1, 7), radix: 16) + 0xFF000000).withOpacity(0.1),
            borderRadius: BorderRadius.circular(12),
          ),
          child: Center(
            child: Text(icon, style: const TextStyle(fontSize: 28)),
          ),
        ),
        title: Text(displayName, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Text('Level: $level'),
            const SizedBox(height: 4),
            Text('System role: ${role == RoleConstants.superAdmin || role == RoleConstants.admin ? 'Yes' : 'No'}',
                style: const TextStyle(fontSize: 12)),
          ],
        ),
        trailing: PopupMenuButton<String>(
          onSelected: (value) {
            if (value == 'permissions') {
              _openPermissionsDialog(role);
            }
          },
          itemBuilder: (context) => [
            const PopupMenuItem(value: 'permissions', child: Text('Manage Permissions')),
            if (role != RoleConstants.superAdmin && role != RoleConstants.admin)
              const PopupMenuItem(value: 'delete', child: Text('Delete', style: TextStyle(color: Colors.red))),
          ],
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    final roles = RoleConstants.allRoles;
    
    return Scaffold(
      appBar: AppBar(
        title: const Text('Role Management'),
        actions: [
          IconButton(
            icon: const Icon(Icons.add),
            onPressed: () {},
          ),
        ],
      ),
      body: ListView.builder(
        padding: const EdgeInsets.all(16),
        itemCount: roles.length,
        itemBuilder: (context, index) => _buildRoleCard(roles[index]),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {},
        child: const Icon(Icons.add),
      ),
    );
  }
}