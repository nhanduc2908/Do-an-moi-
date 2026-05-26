import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'log_model.freezed.dart';
part 'log_model.g.dart';

@freezed
class LogModel with _$LogModel {
  const factory LogModel({
    String? id,
    @JsonKey(name: 'event_type') String? eventType,
    String? severity,
    String? source,
    @JsonKey(name: 'user_id') String? userId,
    @JsonKey(name: 'ip_address') String? ipAddress,
    String? message,
    dynamic details,
    @JsonKey(name: 'logged_at') DateTime? loggedAt,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _LogModel;

  factory LogModel.fromJson(Map<String, dynamic> json) => _$LogModelFromJson(json);
}

extension LogModelX on LogModel {
  bool get isCritical => severity?.toLowerCase() == 'critical';
  bool get isHigh => severity?.toLowerCase() == 'high';
  bool get isMedium => severity?.toLowerCase() == 'medium';
  bool get isLow => severity?.toLowerCase() == 'low';
  
  Color get severityColor {
    switch (severity?.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow;
      case 'low': return Colors.green;
      default: return Colors.grey;
    }
  }
}