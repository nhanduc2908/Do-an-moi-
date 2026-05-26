import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'role_checker.dart';
import '../../presentation/providers/auth_provider.dart';
import '../../presentation/screens/unauthorized_screen.dart';

class RoleGuard extends ConsumerWidget {
  final Widget child;
  final List<String> allowedRoles;
  final int? minLevel;
  final Widget? unauthorizedWidget;

  const RoleGuard({
    super.key,
    required this.child,
    required this.allowedRoles,
    this.minLevel,
    this.unauthorizedWidget,
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final userRole = ref.watch(authProvider).user?.role;
    
    final hasAccess = _checkAccess(userRole);
    
    if (!hasAccess) {
      return unauthorizedWidget ?? const UnauthorizedScreen();
    }
    
    return child;
  }

  bool _checkAccess(String? userRole) {
    if (userRole == null) return false;
    if (allowedRoles.contains(userRole)) return true;
    if (minLevel != null && RoleChecker.getRoleLevel(userRole) >= minLevel!) return true;
    return false;
  }
}

class RoleBuilder extends StatelessWidget {
  final Map<String, WidgetBuilder> roleBuilders;
  final WidgetBuilder? fallbackBuilder;

  const RoleBuilder({
    super.key,
    required this.roleBuilders,
    this.fallbackBuilder,
  });

  @override
  Widget build(BuildContext context) {
    return Consumer(
      builder: (context, ref, child) {
        final userRole = ref.watch(authProvider).user?.role ?? 'viewer';
        final builder = roleBuilders[userRole] ?? fallbackBuilder;
        return builder != null ? builder(context) : const UnauthorizedScreen();
      },
    );
  }
}