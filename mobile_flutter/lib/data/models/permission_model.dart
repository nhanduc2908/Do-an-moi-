import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'permission_model.freezed.dart';
part 'permission_model.g.dart';

@freezed
class PermissionModel with _$PermissionModel {
  const factory PermissionModel({
    int? id,
    String? name,
    @JsonKey(name: 'display_name') String? displayName,
    String? description,
    String? module,
    @JsonKey(name: 'guard_name') String? guardName,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _PermissionModel;

  factory PermissionModel.fromJson(Map<String, dynamic> json) => _$PermissionModelFromJson(json);
}

extension PermissionModelX on PermissionModel {
  String get action {
    final parts = name?.split('.') ?? [];
    return parts.length > 1 ? parts.last : name ?? '';
  }
  
  String get resource {
    final parts = name?.split('.') ?? [];
    return parts.isNotEmpty ? parts.first : '';
  }
}