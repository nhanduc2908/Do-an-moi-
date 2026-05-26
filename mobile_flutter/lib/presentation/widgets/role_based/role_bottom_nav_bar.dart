// Đường dẫn: mobile_flutter/lib/presentation/widgets/role_based/role_bottom_nav_bar.dart

import 'package:flutter/material.dart';
import '../../../core/constants/role_constants.dart';

class RoleBottomNavBar extends StatelessWidget {
  final int currentIndex;
  final Function(int) onTap;
  final String role;

  const RoleBottomNavBar({
    super.key,
    required this.currentIndex,
    required this.onTap,
    required this.role,
  });

  @override
  Widget build(BuildContext context) {
    final items = _getNavItems();

    return BottomNavigationBar(
      currentIndex: currentIndex,
      onTap: onTap,
      type: BottomNavigationBarType.fixed,
      items: items.map((item) => BottomNavigationBarItem(
        icon: Icon(item.icon),
        label: item.label,
      )).toList(),
    );
  }

  List<NavItem> _getNavItems() {
    switch (role) {
      case RoleConstants.superAdmin:
      case RoleConstants.admin:
        return const [
          NavItem(icon: Icons.dashboard, label: 'Dashboard'),
          NavItem(icon: Icons.people, label: 'Users'),
          NavItem(icon: Icons.admin_panel_settings, label: 'Admin'),
          NavItem(icon: Icons.person, label: 'Profile'),
        ];
      case RoleConstants.securityManager:
        return const [
          NavItem(icon: Icons.dashboard, label: 'Dashboard'),
          NavItem(icon: Icons.warning, label: 'Incidents'),
          NavItem(icon: Icons.group, label: 'Team'),
          NavItem(icon: Icons.person, label: 'Profile'),
        ];
      case RoleConstants.complianceOfficer:
        return const [
          NavItem(icon: Icons.dashboard, label: 'Dashboard'),
          NavItem(icon: Icons.checklist, label: 'Compliance'),
          NavItem(icon: Icons.assessment, label: 'Audit'),
          NavItem(icon: Icons.person, label: 'Profile'),
        ];
      default:
        return const [
          NavItem(icon: Icons.dashboard, label: 'Dashboard'),
          NavItem(icon: Icons.security, label: 'Security'),
          NavItem(icon: Icons.description, label: 'Reports'),
          NavItem(icon: Icons.person, label: 'Profile'),
        ];
    }
  }
}

class NavItem {
  final IconData icon;
  final String label;

  const NavItem({required this.icon, required this.label});
}