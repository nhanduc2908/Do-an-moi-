import 'package:flutter/material.dart';
import '../../../core/constants/role_constants.dart';

class RoleAvatar extends StatelessWidget {
  final String role;
  final double radius;

  const RoleAvatar({
    super.key,
    required this.role,
    this.radius = 24,
  });

  @override
  Widget build(BuildContext context) {
    final icon = RoleConstants.roleIcons[role] ?? '👤';
    final color = RoleConstants.roleColors[role] ?? '#6c757d';

    return CircleAvatar(
      radius: radius,
      backgroundColor: Color(int.parse(color.substring(1, 7), radix: 16) + 0xFF000000).withOpacity(0.2),
      child: Text(
        icon,
        style: TextStyle(fontSize: radius * 0.8),
      ),
    );
  }
}