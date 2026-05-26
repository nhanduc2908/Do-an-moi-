import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'device_model.freezed.dart';
part 'device_model.g.dart';

@freezed
class DeviceModel with _$DeviceModel {
  const factory DeviceModel({
    String? id,
    @JsonKey(name: 'device_name') String? deviceName,
    @JsonKey(name: 'device_type') String? deviceType,
    @JsonKey(name: 'device_id') String? deviceId,
    @JsonKey(name: 'os_version') String? osVersion,
    @JsonKey(name: 'is_trusted') bool? isTrusted,
    @JsonKey(name: 'last_used_at') DateTime? lastUsedAt,
    String? fingerprint,
    @JsonKey(name: 'user_id') String? userId,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _DeviceModel;

  factory DeviceModel.fromJson(Map<String, dynamic> json) => _$DeviceModelFromJson(json);
}

extension DeviceModelX on DeviceModel {
  bool get isTrustedDevice => isTrusted == true;
  String get deviceTypeDisplay {
    switch (deviceType) {
      case 'mobile': return 'Mobile';
      case 'tablet': return 'Tablet';
      case 'desktop': return 'Desktop';
      default: return deviceType ?? 'Unknown';
    }
  }
}