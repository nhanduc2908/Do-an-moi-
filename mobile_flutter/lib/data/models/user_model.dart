import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'user_model.freezed.dart';
part 'user_model.g.dart';

@freezed
class UserModel with _$UserModel {
  const factory UserModel({
    String? id,
    String? name,
    String? email,
    String? avatar,
    String? department,
    String? position,
    String? phone,
    String? status,
    bool? mfaEnabled,
    String? role,
    @JsonKey(name: 'last_login_at') DateTime? lastLoginAt,
    @JsonKey(name: 'created_at') DateTime? createdAt,
    @JsonKey(name: 'updated_at') DateTime? updatedAt,
  }) = _UserModel;

  factory UserModel.fromJson(Map<String, dynamic> json) => _$UserModelFromJson(json);
}

extension UserModelX on UserModel {
  bool get isActive => status == 'active';
  bool get isSuspended => status == 'suspended';
  bool get isAdmin => role == 'admin' || role == 'super_admin';
  bool get isSuperAdmin => role == 'super_admin';
  
  String get displayName => name ?? email ?? 'Unknown';
  String get initials {
    if (name == null) return '?';
    final parts = name!.split(' ');
    if (parts.length == 1) return parts[0][0].toUpperCase();
    return '${parts[0][0]}${parts[1][0]}'.toUpperCase();
  }
  
  int get roleLevel {
    const levels = {
      'super_admin': 100, 'admin': 90, 'security_manager': 80,
      'risk_manager': 75, 'compliance_officer': 70, 'security_analyst': 60,
      'incident_responder': 55, 'vulnerability_scanner': 45, 'auditor': 50,
      'viewer': 10,
    };
    return levels[role] ?? 0;
  }
}