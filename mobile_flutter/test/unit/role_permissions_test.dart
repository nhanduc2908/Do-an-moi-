// Đường dẫn: mobile_flutter/test/unit/role_permissions_test.dart

import 'package:flutter_test/flutter_test.dart';
import 'package:security_evaluation_app/core/constants/role_constants.dart';
import 'package:security_evaluation_app/core/role/role_permissions.dart';
import 'package:security_evaluation_app/core/role/role_checker.dart';

void main() {
  group('RolePermissions Tests', () {
    test('Super admin has all permissions', () {
      expect(RolePermissions.hasPermission(RoleConstants.superAdmin, 'any.permission'), true);
    });

    test('Viewer has limited permissions', () {
      expect(RolePermissions.hasPermission(RoleConstants.viewer, 'dashboard.view'), true);
      expect(RolePermissions.hasPermission(RoleConstants.viewer, 'admin.access'), false);
    });

    test('Admin has user management permissions', () {
      expect(RolePermissions.hasPermission(RoleConstants.admin, 'user.view'), true);
      expect(RolePermissions.hasPermission(RoleConstants.admin, 'user.create'), true);
      expect(RolePermissions.hasPermission(RoleConstants.admin, 'user.edit'), true);
      expect(RolePermissions.hasPermission(RoleConstants.admin, 'user.delete'), true);
    });

    test('Security manager has incident permissions', () {
      expect(RolePermissions.hasPermission(RoleConstants.securityManager, 'incident.view'), true);
      expect(RolePermissions.hasPermission(RoleConstants.securityManager, 'incident.create'), true);
    });
  });

  group('RoleChecker Tests', () {
    test('isAdmin returns true for admin role', () {
      expect(RoleChecker.isAdmin(RoleConstants.admin), true);
      expect(RoleChecker.isAdmin(RoleConstants.superAdmin), true);
    });

    test('isAdmin returns false for viewer role', () {
      expect(RoleChecker.isAdmin(RoleConstants.viewer), false);
    });

    test('getRoleLevel returns correct level', () {
      expect(RoleChecker.getRoleLevel(RoleConstants.superAdmin), 100);
      expect(RoleChecker.getRoleLevel(RoleConstants.viewer), 10);
    });

    test('hasHigherLevel returns true for higher level', () {
      expect(RoleChecker.hasHigherLevel(RoleConstants.superAdmin, RoleConstants.viewer), true);
    });

    test('hasLowerLevel returns true for lower level', () {
      expect(RoleChecker.hasLowerLevel(RoleConstants.viewer, RoleConstants.superAdmin), true);
    });

    test('getDashboardRoute returns correct route', () {
      expect(RoleChecker.getDashboardRoute(RoleConstants.superAdmin), '/super-admin');
      expect(RoleChecker.getDashboardRoute(RoleConstants.viewer), '/viewer');
    });
  });
}