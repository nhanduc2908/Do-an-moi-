import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'role_model.freezed.dart';
part 'role_model.g.dart';

@freezed
class RoleModel with _$RoleModel {
  const factory RoleModel({
    int? id,
    String? name,
    @JsonKey(name: 'display_name') String? displayName,
    String? description,
    int? level,
    @JsonKey(name: 'is_system_role') bool? isSystemRole,
    @JsonKey(name: 'created_at') DateTime? createdAt,
    @JsonKey(name: 'updated_at') DateTime? updatedAt,
  }) = _RoleModel;

  factory RoleModel.fromJson(Map<String, dynamic> json) => _$RoleModelFromJson(json);
}

extension RoleModelX on RoleModel {
  String get color {
    const colors = {
      'super_admin': '#DC3545', 'admin': '#E74C3C', 'security_manager': '#E67E22',
      'compliance_officer': '#2ECC71', 'risk_manager': '#F39C12', 'security_analyst': '#3498DB',
      'incident_responder': '#E84393', 'vulnerability_scanner': '#1ABC9C', 'auditor': '#95A5A6',
      'viewer': '#7F8C8D',
    };
    return colors[name] ?? '#6C757D';
  }
  
  String get icon {
    const icons = {
      'super_admin': '👑', 'admin': '⚙️', 'security_manager': '🛡️',
      'compliance_officer': '📋', 'risk_manager': '📊', 'security_analyst': '🔍',
      'incident_responder': '🚨', 'vulnerability_scanner': '🔬', 'auditor': '📜',
      'viewer': '👁️',
    };
    return icons[name] ?? '👤';
  }
}