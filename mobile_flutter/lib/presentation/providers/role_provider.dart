import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../core/constants/role_constants.dart';
import 'auth_provider.dart';

final roleProvider = Provider<String?>((ref) {
  final user = ref.watch(authProvider).user;
  return user?.role;
});

final roleLevelProvider = Provider<int>((ref) {
  final role = ref.watch(roleProvider);
  return RoleConstants.roleLevels[role] ?? 0;
});

final roleDisplayNameProvider = Provider<String>((ref) {
  final role = ref.watch(roleProvider);
  return RoleConstants.roleDisplayNames[role] ?? 'Unknown';
});

final roleIconProvider = Provider<String>((ref) {
  final role = ref.watch(roleProvider);
  return RoleConstants.roleIcons[role] ?? '👤';
});

final roleColorProvider = Provider<String>((ref) {
  final role = ref.watch(roleProvider);
  return RoleConstants.roleColors[role] ?? '#6c757d';
});

final isAdminProvider = Provider<bool>((ref) {
  final role = ref.watch(roleProvider);
  return role == RoleConstants.admin || role == RoleConstants.superAdmin;
});

final isSecurityTeamProvider = Provider<bool>((ref) {
  final role = ref.watch(roleProvider);
  return [
    RoleConstants.securityManager,
    RoleConstants.securityAnalyst,
    RoleConstants.incidentResponder,
  ].contains(role);
});

final canManageUsersProvider = Provider<bool>((ref) {
  final role = ref.watch(roleProvider);
  return role == RoleConstants.superAdmin || role == RoleConstants.admin;
});

final canManageRolesProvider = Provider<bool>((ref) {
  final role = ref.watch(roleProvider);
  return role == RoleConstants.superAdmin || role == RoleConstants.admin;
});

final canViewAuditLogsProvider = Provider<bool>((ref) {
  final role = ref.watch(roleProvider);
  return role == RoleConstants.superAdmin || role == RoleConstants.admin || role == RoleConstants.auditor;
});