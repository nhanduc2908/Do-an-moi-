import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'session_model.freezed.dart';
part 'session_model.g.dart';

@freezed
class SessionModel with _$SessionModel {
  const factory SessionModel({
    String? id,
    @JsonKey(name: 'session_id') String? sessionId,
    @JsonKey(name: 'user_id') String? userId,
    @JsonKey(name: 'ip_address') String? ipAddress,
    @JsonKey(name: 'user_agent') String? userAgent,
    @JsonKey(name: 'device_type') String? deviceType,
    @JsonKey(name: 'is_active') bool? isActive,
    @JsonKey(name: 'last_activity') DateTime? lastActivity,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _SessionModel;

  factory SessionModel.fromJson(Map<String, dynamic> json) => _$SessionModelFromJson(json);
}

extension SessionModelX on SessionModel {
  bool get isActiveSession => isActive == true;
  String get deviceInfo => '$deviceType - $ipAddress';
}