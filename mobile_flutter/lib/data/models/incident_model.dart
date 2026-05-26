import 'package:json_annotation/json_annotation.dart';
import 'package:freezed_annotation/freezed_annotation.dart';

part 'incident_model.freezed.dart';
part 'incident_model.g.dart';

@freezed
class IncidentModel with _$IncidentModel {
  const factory IncidentModel({
    String? id,
    @JsonKey(name: 'incident_code') String? incidentCode,
    String? title,
    String? description,
    String? severity,
    String? status,
    String? category,
    @JsonKey(name: 'detected_at') DateTime? detectedAt,
    @JsonKey(name: 'reported_at') DateTime? reportedAt,
    @JsonKey(name: 'responded_at') DateTime? respondedAt,
    @JsonKey(name: 'resolved_at') DateTime? resolvedAt,
    @JsonKey(name: 'reported_by') String? reportedBy,
    @JsonKey(name: 'assigned_to') String? assignedTo,
    @JsonKey(name: 'resolution_summary') String? resolutionSummary,
    @JsonKey(name: 'created_at') DateTime? createdAt,
  }) = _IncidentModel;

  factory IncidentModel.fromJson(Map<String, dynamic> json) => _$IncidentModelFromJson(json);
}

extension IncidentModelX on IncidentModel {
  bool get isOpen => status == 'open';
  bool get isInvestigating => status == 'investigating';
  bool get isResolved => status == 'resolved';
  
  String get severityDisplay {
    switch (severity?.toLowerCase()) {
      case 'critical': return 'Nghiêm trọng';
      case 'high': return 'Cao';
      case 'medium': return 'Trung bình';
      case 'low': return 'Thấp';
      default: return severity ?? 'Unknown';
    }
  }
  
  Color get severityColor {
    switch (severity?.toLowerCase()) {
      case 'critical': return Colors.red;
      case 'high': return Colors.orange;
      case 'medium': return Colors.yellow;
      case 'low': return Colors.green;
      default: return Colors.grey;
    }
  }
  
  String get statusDisplay {
    switch (status?.toLowerCase()) {
      case 'open': return 'Đang mở';
      case 'investigating': return 'Đang điều tra';
      case 'resolved': return 'Đã giải quyết';
      case 'closed': return 'Đã đóng';
      default: return status ?? 'Unknown';
    }
  }
  
  Color get statusColor {
    switch (status?.toLowerCase()) {
      case 'open': return Colors.red;
      case 'investigating': return Colors.orange;
      case 'resolved': return Colors.green;
      case 'closed': return Colors.blue;
      default: return Colors.grey;
    }
  }
}